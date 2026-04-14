<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CustomForgotPasswordController extends Controller
{
    // Mostrar formulario para solicitar código
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // Enviar código numérico al correo
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generar código de 6 dígitos
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Guardar en la base de datos
        PasswordResetCode::create([
            'email' => $user->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
            'used' => false,
        ]);

        // Enviar correo (usando Mailtrap en desarrollo)
        Mail::send('emails.reset-code', ['code' => $code, 'user' => $user], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Código de recuperación de contraseña');
        });

        return redirect()->route('password.reset.form')->with('success', 'Se ha enviado un código de 6 dígitos a tu correo electrónico.');
    }

    // Mostrar formulario para ingresar código y nueva contraseña
    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    // Verificar código y actualizar contraseña
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
            'password' => 'required|confirmed|min:8',
        ]);

        // Buscar el código válido
        $resetCode = PasswordResetCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetCode) {
            return back()->withErrors(['code' => 'El código es inválido o ha expirado.']);
        }

        // Actualizar la contraseña del usuario
        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Marcar el código como usado
        $resetCode->used = true;
        $resetCode->save();

        return redirect()->route('login')->with('success', 'Tu contraseña ha sido actualizada. Ahora puedes iniciar sesión.');
    }
}