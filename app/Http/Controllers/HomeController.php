<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;

class HomeController extends Controller
{
    public UserRepositoryInterface $userRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    public function index()
    {
        $loggedInUser = auth()->user();

        if ($loggedInUser) {
            $conversations = $this->userRepository->getUserConversations($loggedInUser);
            $friendsToAdd = $this->userRepository->getNotBefriendedUsers($loggedInUser);
            $friendRequestsReceived = $this->userRepository->getFriendRequestsReceived($loggedInUser);
            $friendRequestsSent = $this->userRepository->getFriendRequestsSent($loggedInUser);
            $friends = $this->userRepository->getFriends($loggedInUser);
        } else {
            $conversations = [];
            $friendsToAdd = $this->userRepository->getUsersWithLimit(5);
            $friendRequestsReceived = [];
            $friendRequestsSent = [];
            $friends = [];
        }

        return view('home', [
            'friendsToAdd' => $friendsToAdd,
            'conversations' => $conversations,
            'conversation' => null,
            'conversation_users' => null,
            'messages' => [],
            'friendRequestsReceived' => $friendRequestsReceived,
            'friendRequestsSent' => $friendRequestsSent,
            'friends' => $friends,
        ]);
    }
}
