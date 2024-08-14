<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public UserRepositoryInterface $userRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    public function show(User $user)
    {

        if ($user->id != auth()->user()->id) {
            return redirect()->back();
        }

        $loggedInUser = auth()->user();

        $conversations = $this->userRepository->getUserConversations($loggedInUser);
        $friendsToAdd = $this->userRepository->getNotBefriendedUsers($loggedInUser);

        return view("profile_settings", compact(
            "user",
            "conversations",
            'friendsToAdd',
        )
        );
    }

    public function toggleTFA(Request $request, User $user)
    {

        $validated = $request->validate([
            'phone_number' => 'required|min:9|max:9',
        ]);

        $this->userRepository->changeUserPhoneNumber(auth()->user(), $validated['phone_number']);
        $this->userRepository->changeTfaState(auth()->user(), $request->get('tfa_state') ? true : false);

        return redirect()->back();
    }
}
