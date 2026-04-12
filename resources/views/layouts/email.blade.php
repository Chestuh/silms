<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('subject', 'Notification')</title>
</head>
<body style="font-family: sans-serif; color: #333;">
    <div style="max-width:600px; margin:0 auto; padding:20px;">
        <div style="padding:20px; border:1px solid #eee; border-radius:6px; background:#fff;">
            @yield('content')
        </div>
        <p style="color:#888; font-size:12px; margin-top:12px;">This message was sent by the CSP Learning Portal.</p>
    </div>
</body>
</html>
