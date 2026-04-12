<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credential request letter — {{ $credential_request->credential_type }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #000; }
        .letterhead { text-align: center; margin-bottom: 2em; border-bottom: 1px solid #333; padding-bottom: 1em; }
        .letterhead h1 { font-size: 18pt; margin: 0; }
        .letterhead p { margin: 0.25em 0; font-size: 11pt; color: #444; }
        .date { margin-bottom: 1.5em; }
        .to { margin-bottom: 1.5em; }
        .body { text-align: justify; margin-bottom: 2em; }
        .signature { margin-top: 2.5em; }
        .signature-line { border-bottom: 1px solid #000; width: 220px; margin-top: 0.5em; margin-bottom: 0.25em; }
        .signature-label { font-size: 10pt; color: #444; }
        .signature-name { font-size: 11pt; font-weight: 600; color: #222; margin-top: 0.25em; margin-bottom: 0.25em; }
        .signature-img { max-height: 50px; margin-bottom: 0.25em; }
        @media print { body { margin: 20px; } .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button type="button" onclick="window.print();" style="padding: 8px 16px; cursor: pointer; background: #0d6efd; color: #fff; border: none; border-radius: 4px;">Print / Save as PDF</button>
        <span style="margin-left: 10px; color: #666; font-size: 11pt;">Use this button or Ctrl+P, then choose “Save as PDF” to create the letter for the registrar.</span>
        <form method="POST" action="{{ route('admin.credentials.send', $credential_request) }}" style="display: inline-block; margin-left: 16px;">
            @csrf
            <button type="submit" style="padding: 8px 16px; cursor: pointer; background: #198754; color: #fff; border: none; border-radius: 4px;">Send signed letter to student</button>
        </form>
        <span style="margin-left: 10px; color: #666; font-size: 11pt;">After signing, send the PDF attachment to the student so they can download it from their Credential Request page.</span>
    </div>

    <div class="letterhead">
        <h1>{{ config('app.name') }}</h1>
        <p>{{ config('app.tagline') }}</p>
    </div>

    <div class="date">{{ now()->format('F j, Y') }}</div>

    <div class="to">
        <strong>To the Office of the Registrar</strong><br>
        {{ config('app.name') }}
    </div>

    <div class="body">
        <p><strong>Re: Request for {{ $credential_request->credential_type }}</strong></p>
        <p>This is to certify that <strong>{{ $credential_request->student->user->name ?? '—' }}</strong> (Student No. {{ $credential_request->student->student_number ?? '—' }}) has requested the following credential from this institution:</p>
        <p><strong>{{ $credential_request->credential_type }}</strong></p>
        <p>We recommend that the above credential be released to the student upon completion of your office’s requirements. This letter serves as the administration’s authorization for the release of the requested credential to the registrar for processing.</p>
    </div>

    <div class="signature">
        @if(isset($signaturePath) || file_exists(storage_path('app/public/signature.png')))
            <img src="{{ isset($signaturePath) ? $signaturePath : asset('storage/signature.png') }}" alt="Signature" class="signature-img">
        @endif
        <div class="signature-name">{{ $signerName ?? (config('app.name') . ' Administration') }}</div>
        <div class="signature-line"></div>
        <div class="signature-label">Authorized signature</div>
    </div>

    <script>
        document.querySelector('button') && document.querySelector('button').addEventListener('click', function() { window.print(); });
    </script>
</body>
</html>
