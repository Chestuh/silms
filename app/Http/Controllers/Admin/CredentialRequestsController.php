<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CredentialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PdfFacade;

class CredentialRequestsController extends Controller
{
    public function index(Request $request)
    {
        $requests = CredentialRequest::with('student.user')
            ->whereIn('status', ['pending', 'processing'])
            ->orderByDesc('created_at')
            ->paginate(15);
        return view('admin.credentials.index', compact('requests'));
    }

    public function show(CredentialRequest $credential_request)
    {
        $credential_request->load('student.user');
        return view('admin.credentials.show', compact('credential_request'));
    }

    public function letter(CredentialRequest $credential_request)
    {
        $credential_request->load('student.user');
        // load signer name if present
        $signerName = null;
        if (Storage::disk('public')->exists('signature.json')) {
            try {
                $meta = json_decode(Storage::disk('public')->get('signature.json'), true);
                $signerName = $meta['name'] ?? null;
            } catch (\Throwable $e) {
                $signerName = null;
            }
        }

        // If a signed/generated letter file exists, serve it directly for download/view.
        if ($credential_request->letter_path && Storage::disk('local')->exists($credential_request->letter_path)) {
            $full = storage_path('app/' . $credential_request->letter_path);
            return response()->file($full);
        }

        return view('admin.credentials.letter', compact('credential_request', 'signerName'));
    }

    public function sendToStudent(CredentialRequest $credential_request)
    {
        $credential_request->load('student.user');
        // If a signed letter was already uploaded by admin, do not regenerate PDF — just mark ready
        if ($credential_request->letter_path && Storage::disk('local')->exists($credential_request->letter_path)) {
            $credential_request->update(['status' => 'ready']);
            return redirect()
                ->route('admin.credentials.show', $credential_request)
                ->with('success', 'Signed letter is ready and has been sent to the student.');
        }

        // Otherwise generate PDF (including signature image if present) and store it
        $signaturePath = null;
        $signerName = null;
        if (Storage::disk('public')->exists('signature.png')) {
            $signaturePath = 'file://' . str_replace('\\', '/', storage_path('app/public/signature.png'));
        }
        if (Storage::disk('public')->exists('signature.json')) {
            try {
                $meta = json_decode(Storage::disk('public')->get('signature.json'), true);
                $signerName = $meta['name'] ?? null;
            } catch (\Throwable $e) {
                $signerName = null;
            }
        }

        $pdfDoc = PdfFacade::loadView('admin.credentials.letter-pdf', [
            'credential_request' => $credential_request,
            'signaturePath' => $signaturePath,
            'signerName' => $signerName,
        ])->setPaper('a4')->setWarnings(false);

        $dir = 'credentials/letters';
        Storage::disk('local')->makeDirectory($dir);
        $filename = 'credential-letter-' . $credential_request->id . '-' . now()->format('Y-m-d') . '.pdf';
        $path = $dir . '/' . $filename;
        $fullPath = storage_path('app/' . $path);
        $pdfDoc->save($fullPath);

        $credential_request->update([
            'letter_path' => $path,
            'status' => 'ready',
        ]);

        return redirect()
            ->route('admin.credentials.show', $credential_request)
            ->with('success', 'Signed letter has been generated and is ready for the student.');
    }

    public function uploadSigned(Request $request, CredentialRequest $credential_request)
    {
        $request->validate([
            'signed_letter' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $file = $request->file('signed_letter');
        $dir = 'credentials/letters';
        Storage::disk('local')->makeDirectory($dir);
        $filename = 'credential-letter-' . $credential_request->id . '-signed-' . now()->format('YmdHis') . '.pdf';
        $path = $dir . '/' . $filename;
        $file->storeAs($dir, $filename, 'local');

        // update the request with the uploaded signed letter path; keep status as processing until admin clicks send
        $credential_request->update([
            'letter_path' => $path,
            'status' => 'processing',
        ]);

        return redirect()->route('admin.credentials.show', $credential_request)->with('success', 'Signed letter uploaded. You may now Send signed letter to student.');
    }

    /**
     * Save an admin e-signature image (base64 PNG) to public storage
     */
    public function saveSignature(Request $request)
    {
        $request->validate([
            'signature_data' => ['required', 'string'],
        ]);

        $data = $request->input('signature_data');
        $signerName = trim($request->input('signer_name') ?? '');
        if (preg_match('/^data:image\/png;base64,/', $data)) {
            $data = preg_replace('/^data:image\/png;base64,/', '', $data);
        }
        $decoded = base64_decode($data);
        if ($decoded === false) {
            return back()->withErrors(['signature_data' => 'Invalid signature data']);
        }

        // store as public so letter view can reference via asset('storage/signature.png')
        Storage::disk('public')->put('signature.png', $decoded);
        // store signer name metadata
        Storage::disk('public')->put('signature.json', json_encode(['name' => $signerName]));

        return back()->with('success', 'Signature saved. It will be included when generating letters.');
    }
}
