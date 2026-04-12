<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Credential request letter — {{ $credential_request->credential_type }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12pt; line-height: 1.5; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #000; }
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
    </style>
</head>
<body>
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
        <p>We recommend that the above credential be released to the student upon completion of your office's requirements. This letter serves as the administration's authorization for the release of the requested credential to the registrar for processing.</p>
    </div>

    <div class="signature">
        @if(!empty($signaturePath))
            <img src="{{ $signaturePath }}" alt="Signature" class="signature-img" style="max-height: 50px;">
        @endif
        <div class="signature-name">{{ $signerName ?? (config('app.name') . ' Administration') }}</div>
        <div class="signature-line"></div>
        <div class="signature-label">Authorized signature</div>
    </div>
</body>
</html>
