<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\SMSApiServiceProvider;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public SMSApiServiceProvider $smsSender;
    public UserRepositoryInterface $userRepository;

    public function __construct()
    {
        $this->smsSender = app(SMSApiServiceProvider::class);
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    public function register(Request $request)
    {
        return view("auth.register");
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            "name" => "required",
            "email" => "email|required|unique:users,email",
            "password" => "confirmed|required|min:2|max:40",
        ]);

        $this->userRepository->create($validated);

        return redirect(route('login'))->with("success", "Registered successfully!");
    }

    public function login(Request $request)
    {
        return view("auth.login");
    }

    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            "email" => "email|required",
            "password" => "required|min:2|max:40",
        ]);

        $user = $this->userRepository->getUserByEmail($validated["email"]);
        if ($user && app('hash')->check($validated["password"], $user->password) && $user->tfa_state) {

            session()->put('user_email', $validated['email']);

            $generatedTfaCode = strval(rand(0, 999999));
            if (strlen($generatedTfaCode) < 6) {
                while (strlen($generatedTfaCode) < 6) {
                    $generatedTfaCode = "0" . $generatedTfaCode;
                }
            }

            $this->userRepository->changeTfaCode($user, $generatedTfaCode);

            $this->smsSender->sendSMS($user->phone_number, "Your verification code is ". $generatedTfaCode);

            return view('auth.two_factor_auth');
        }

        if (auth()->attempt($validated)) {

            $request->session()->regenerate();

            return redirect(route('home'))->with("success", "Logged in successfully!");

        } else {
            return redirect(route('login'))->with('error', 'Incorrect email or password.');
        }
    }

    public function checkTFA(Request $request)
    {

        $user = $this->userRepository->getUserByEmail(session('user_email'));

        if ($request->get('code') == $user->tfa_code) {

            auth()->login($user);

            session()->forget('user_email');

            return redirect(route('home'))->with("success", "Logged in successfully!");
        } else {

            session()->forget('user_email');

            return redirect(route('login'))->with('error', 'Incorrect code given.');
        }
    }

    public function logout(Request $request)
    {

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect(route('home'))->with("success", "Logged out successfully!");
    }
}