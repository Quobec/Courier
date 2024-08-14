<?php

namespace App\Interfaces;

interface MessageRepositoryInterface
{
    public function create(string $content, string $conversation_id, string $user_id);

    public function getMessagesWithUsers(string $conversation_id);
    
}
