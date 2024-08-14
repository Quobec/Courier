<?php

namespace App\Http\Controllers;

use App\Events\NewMessageEvent;
use App\Models\Conversation;
use App\Interfaces\ConversationRepositoryInterface;
use App\Interfaces\MessageRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public ConversationRepositoryInterface $conversationRepository;
    public UserRepositoryInterface $userRepository;
    public MessageRepositoryInterface $MessageRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
        $this->MessageRepository = app(MessageRepositoryInterface::class);
        $this->conversationRepository = app(ConversationRepositoryInterface::class);
    }


    public function store(Request $request, Conversation $conversation)
    {

        $validated = $request->validate([
            "content" => "required",
        ]);

        $this->MessageRepository->create($validated['content'], $conversation->id, auth()->user()->id);

        broadcast(new NewMessageEvent($validated['content'], auth()->user()->name, "chat" . $conversation->id));

        return redirect()->route('conversations.message.show', $conversation->id);
    }

    public function show(Conversation $conversation)
    {

        $loggedInUser = auth()->user();

        if (!($this->userRepository->checkIfUserBelongsToConversation($loggedInUser, $conversation))) {
            return redirect()->back();
        }

        $conversations = $this->userRepository->getUserConversations($loggedInUser);
        $messages = $this->MessageRepository->getMessagesWithUsers($conversation->id);
        $friendsToAdd = $this->userRepository->getNotBefriendedUsers($loggedInUser);
        $conversation_users = $this->conversationRepository->getUsers($conversation);
        $friendRequestsReceived = $this->userRepository->getFriendRequestsReceived($loggedInUser);
        $friendRequestsSent = $this->userRepository->getFriendRequestsSent($loggedInUser);
        $friends = $this->userRepository->getFriends($loggedInUser);

        return view(
            'home',
            compact(
                'messages',
                'conversations',
                'conversation',
                'conversation_users',
                'friendsToAdd',
                'friendRequestsReceived',
                'friendRequestsSent',
                'friends',
            )
        );
    }
}
