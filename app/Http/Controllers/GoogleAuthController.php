<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public UserRepositoryInterface $userRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {

        if ($request['error']) {
            return redirect(route('home'))->with('error', 'An error has occured.');
        }

        $googleUser = Socialite::driver('google')->user();

        if ($this->userRepository->getUserByEmail($googleUser->email)) {

            auth()->login($this->userRepository->getUserByEmail($googleUser->email));

            return redirect(route('home'))->with('success', 'Logged in successfully.');
        } else {
            $user = $this->userRepository->create([
                'google_id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => strval(rand(100, 999999)),
            ]);
            auth()->login($user);
            return redirect(route('home'))->with('success', 'Registered and logged in successfully.');
        }
    }

    public function login()
    {
        return Socialite::driver('google')->redirect();
    }
}
