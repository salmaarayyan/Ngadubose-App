
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive OAuth Setup</title>
</head>
<body>
    <h2>Refresh Token Google Drive</h2>

    <p>
        Salin token berikut ke GOOGLE_DRIVE_REFRESH_TOKEN di file .env.
        Jangan bagikan token ini kepada orang lain.
    </p>

    <textarea id="refreshToken" rows="6" cols="80" readonly>{{ $refreshToken }}</textarea>

    <br><br>

    <button type="button" onclick="
        navigator.clipboard.writeText(
            document.getElementById('refreshToken').value
        ).then(() => alert('Token berhasil disalin.'));
    ">
        Salin Refresh Token
    </button>
</body>
</html>
