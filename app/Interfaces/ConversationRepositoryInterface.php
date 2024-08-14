<?php

namespace App\Interfaces;
use App\Models\Conversation;
use App\Models\User;

interface ConversationRepositoryInterface
{
    public function create(string $name, array $friends);

    public function getUsers(Conversation $conversation);

    public function getUsersToAdd(Conversation $conversation, $loggedInUser);

    public function update(Conversation $conversation, string $name, array $friends, User $loggedInUser);

    public function getFriends(string $loggedInUser);
}
