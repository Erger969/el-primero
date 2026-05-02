<!DOCTYPE html>
<html>
<head>
    <title>Código de recuperación</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #1A3C5E;
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            color: #555555;
            margin-bottom: 20px;
        }
        .code-box {
            background-color: #f8fafc;
            border: 2px dashed #C4A35A;
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
            display: inline-block;
        }
        .code {
            font-size: 40px;
            font-weight: bold;
            color: #1A3C5E;
            letter-spacing: 8px;
            margin: 0;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #888888;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>UniSocial</h1>
        </div>
        <div class="content">
            <h2>Hola, {{ $user->name }}</h2>
            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Tu código de verificación de 6 dígitos es el siguiente:</p>
            
            <div class="code-box">
                <h1 class="code">{{ $code }}</h1>
            </div>
            
            <p>Este código <strong>expirará en 15 minutos</strong>.</p>
            <p style="font-size: 14px; color: #888;">Si no solicitaste este cambio, puedes ignorar este correo de forma segura. Tu contraseña no cambiará hasta que verifiques este código.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} UniSocial. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>