<?php

namespace App\Repositories;

use App\Interfaces\MessageRepositoryInterface;
use App\Models\Message;

class MessageRepository implements MessageRepositoryInterface
{
    public function create(string $content, string $conversation_id, string $user_id): void
    {
        Message::create([
            'content' => $content,
            'conversation_id' => $conversation_id,
            'user_id' => $user_id,
        ]);
    }

    public function getMessagesWithUsers(string $conversation_id)
    {
        return Message::with('user')->where("conversation_id", $conversation_id)->get();
    }

}
