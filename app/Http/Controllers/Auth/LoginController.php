<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $this->storeActivityLog($request, $user, 'login');

            switch ($user->role) {
                case 'admin':
                    return redirect()->route('dashboard');

                case 'manager':
                    return redirect()->route('dashboard');

                case 'staff':
                    return redirect()->route('dashboard');

                default:
                    return redirect()->route('shop');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $this->storeActivityLog($request, $user, 'logout');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function storeActivityLog(Request $request, ?User $user, string $action): void
    {
        if (!$user) {
            return;
        }

        try {
            ActivityLog::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'action' => $action,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
