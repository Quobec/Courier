<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    public UserRepositoryInterface $userRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
    }
    public function store(Request $request, User $user)
    {
        $loggedInUser = auth()->user();
        $friendToAdd = $user;

        return $this->userRepository->inviteFriend($loggedInUser, $friendToAdd);
    }

    public function accept(User $user)
    {
        $loggedInUser = auth()->user();

        return $this->userRepository->acceptFriendInvite($loggedInUser, $user);
    }

    public function reject(User $user)
    {
        $loggedInUser = auth()->user();

        return $this->userRepository->rejectFriendInvite($loggedInUser, $user);
    }

    public function cancel(User $user)
    {
        $loggedInUser = auth()->user();

        return $this->userRepository->cancelFriendInvite($loggedInUser, $user);
    }
}
