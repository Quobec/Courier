<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Interfaces\ConversationRepositoryInterface;
use Illuminate\Http\Request;

class ConversationController extends Controller
{

    public ConversationRepositoryInterface $conversationRepository;

    public function __construct()
    {
        $this->conversationRepository = app(ConversationRepositoryInterface::class);
    }

    public function create()
    {
        $friends = $this->conversationRepository->getFriends(auth()->user()->id);

        return view('conversation.create', compact('friends'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'friends' => 'required',
            'name' => 'required',
        ]);

        $this->conversationRepository->create($validated['name'], $validated['friends']);

        return redirect()->route('home')->with('success', 'Conversation created successfully!');
    }

    public function add(Conversation $conversation)
    {
        $users = $this->conversationRepository->getUsersToAdd($conversation, auth()->user()->id);

        return view('conversation.add', compact('conversation', 'users'));
    }

    public function update(Conversation $conversation, Request $request)
    {
        $validated = $request->validate([
            'friends' => 'required',
            'name' => 'required',
        ]);

        $this->conversationRepository->update($conversation, $validated['name'], $request->get('friends'), auth()->user());

        return redirect()->route('conversations.message.show', $conversation)->with('success', 'Conversation edited successfully!');
    }
}
