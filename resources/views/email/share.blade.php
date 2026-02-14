<!-- resources/views/emails/share.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>QR Code</title>
</head>
<body>
    <p>Here is your QR code link:</p>
    <a href="{{ $qrCodePath }}">{{ $qrCodePath }}</a>
</body>
</html>
