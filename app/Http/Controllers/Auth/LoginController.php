<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TelegramController;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramUpdates;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function view($notifiable)
    {
        return ["telegram"];
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $url = $request->session()->get('url.intended');

        $credentials = $request->only('username', 'password');

        // guard web
        // dd(Auth::guard('web')->attempt($credentials, true));
        if (Auth::guard('web')->attempt($credentials, true)) {

            if ($url == null)
                return redirect()->route('home');
            else
                return redirect()->intended($url);
        }
        throw ValidationException::withMessages([
            'username' => ['Username atau password salah'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }
}
