<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CredentialRequest;
use Illuminate\Http\Request;

class CredentialsController extends Controller
{
    public function index(Request $request)
    {
        $requests = CredentialRequest::with('student.user')
            ->orderByDesc('created_at')
            ->paginate(15);
        return view('cashier.credentials.index', compact('requests'));
    }

    public function clearPayment(CredentialRequest $credential_request)
    {
        $credential_request->update(['payment_cleared_at' => now()]);
        return redirect()
            ->route('cashier.credentials.index')
            ->with('success', 'Payment cleared for this credential request. Admin may now release the credential.');
    }
}
