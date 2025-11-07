<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $ssoUrl = rtrim(env('SSO_URL'), '/');
        $apiKey = env('SSO_API_KEY');
        $appName = env('SSO_APP_NAME');

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey, // ✅ FIX: gunakan header yang benar
                'Accept'    => 'application/json',
            ])->post($ssoUrl, [
                'username' => $request->username,
                'password' => $request->password,
                'app_name' => $appName,
            ]);

            // Debug log agar mudah dilacak jika masih error
            \Log::debug('🔹 SSO Debug Info', [
                'url_dikirim' => $ssoUrl,
                'header' => ['x-api-key' => $apiKey],
                'data_dikirim' => [
                    'username' => $request->username,
                    'password' => str_repeat('*', strlen($request->password)),
                ],
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['success']) && $result['success'] === true) {
                    return $this->handleSuccessfulLogin($result);
                }

                return back()->withErrors([
                    'username' => $result['message'] ?? 'Login gagal, periksa username dan password.',
                ]);
            }

            return back()->withErrors([
                'username' => 'Login gagal: ' . $response->status(),
                'debug' => $response->body(),
            ]);

        } catch (\Throwable $e) {
            return back()->withErrors([
                'username' => 'Tidak bisa menghubungi server SSO.',
                'debug' => $e->getMessage(),
            ]);
        }
    }

    private function handleSuccessfulLogin(array $result)
    {
        $userData = $result['user'] ?? ($result['data']['user'] ?? null);
        if (!$userData) {
            return back()->withErrors(['username' => 'Format data user dari SSO tidak valid.']);
        }

        $appName = env('SSO_APP_NAME');
        $role = null;

        if (isset($userData['apps']) && is_array($userData['apps'])) {
            foreach ($userData['apps'] as $app) {
                if (($app['app_name'] ?? '') === $appName) {
                    $role = $app['app_role'] ?? null;
                    break;
                }
            }
        }

        $user = User::firstOrCreate(
            ['email' => $userData['email']],
            [
                'name' => $userData['name'] ?? $userData['username'],
                'password' => bcrypt('password-sso'),
            ]
        );

        Auth::login($user);

        session([
            'token' => $result['token'] ?? null,
            'user'  => [
                'username' => $userData['username'],
                'name'     => $userData['name'] ?? '',
                'email'    => $userData['email'] ?? '',
                'role'     => $role ?? 'mahasiswa',
            ],
        ]);

        return match ($role) {
            'superadmin-logbook' => redirect()->intended('/user'),
            'dosen' => redirect()->intended('/dosen'),
            default => redirect()->intended('/user'),
        };
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/login');
    }
}
