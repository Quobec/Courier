<?php

namespace App\Repositories;
use App\Interfaces\ConversationRepositoryInterface;
use App\Models\Conversation;
use App\Models\User;

class ConversationRepository implements ConversationRepositoryInterface
{
    public function __construct(){}


    public function create(string $name, array $friends){
        
        $conversation = Conversation::create([
            'name' => $name,
        ]);

        $conversation->users()->attach(auth()->user()->id);
        foreach ($friends as $friend) {
            $conversation->users()->attach($friend);
        }
    }

    public function getUsers(Conversation $conversation){
        return $conversation->users()->get();
    }

    public function getUsersToAdd(Conversation $conversation, $loggedInUser){
        $loggedInUser = User::find($loggedInUser);
        return $conversation->users()->whereNot("user_id", $loggedInUser->id)->get()->merge($loggedInUser->getFriends());
    }

    public function update(Conversation $conversation, string $name, array $friends, User $loggedInUser){
        $conversation->name = $name;

        $conversation_users = $conversation->users()->get();

        foreach ($conversation_users as $user) {
            if($user->id != $loggedInUser->id){
                $conversation->users()->detach($user);
            }
        }

        foreach ($friends as $friend) {
            $conversation->users()->attach($friend);
        }
        $conversation->save();
    }

    public function getFriends(string $loggedInUser){
        return User::where("id", $loggedInUser)->first()->getFriends();
    }
}
