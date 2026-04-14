<!DOCTYPE html>
<html>
<head>
    <title>Código de recuperación</title>
</head>
<body>
    <h2>Hola {{ $user->name }},</h2>
    <p>Has solicitado restablecer tu contraseña. Tu código de verificación es:</p>
    <h1 style="font-size: 32px; letter-spacing: 5px;">{{ $code }}</h1>
    <p>Este código expirará en 15 minutos.</p>
    <p>Si no solicitaste esto, ignora este mensaje.</p>
</body>
</html>