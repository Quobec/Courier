<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\Conversation;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function getUserByEmail(string $email)
    {
        return User::where("email", $email)->first();
    }

    public function changeTfaCode(User $user, string $code): void
    {
        $user->tfa_code = $code;
        $user->save();
    }

    public function changeTfaState(User $user, bool $state): void
    {
        $user->tfa_state = $state;
        $user->save();
    }

    public function changeUserPhoneNumber(User $user, string $phoneNumber): void
    {
        $user->phone_number = $phoneNumber;
        $user->save();
    }

    public function inviteFriend(User $invitingUser, User $invitedUser)
    {
        if ($invitingUser->friends()->get()->count() < $invitingUser->friend_slots) {
            if (
                $invitingUser->friends()->get()->contains("id", $invitedUser->id) ||
                $invitedUser->friends()->get()->contains("id", $invitingUser->id)
            ) {
                return redirect(route("home"))->with('error', "You already friends or there is already a pending friend request.");
            } else {
                $invitingUser->friends()->attach($invitedUser->id);
                return redirect(route("home"))->with('success', "Friend request sent.");
            }
        } else {
            return redirect(route("home"))->with('error', "Friend limit reached. You can extend it in settings.");
        }
    }

    public function acceptFriendInvite(User $invitedUser, User $invitingUser)
    {
        $invitedUser->befriendedBy()->updateExistingPivot($invitingUser, [
            'confirmed' => true,
        ]);

        return redirect()->back();
    }

    public function rejectFriendInvite(User $invitedUser, User $invitingUser)
    {
        $invitedUser->befriendedBy()->detach($invitingUser);

        return redirect()->back();
    }

    public function cancelFriendInvite( User $invitingUser, User $invitedUser)
    {
        $invitingUser->friends()->detach($invitedUser);

        return redirect()->back();
    }

    public function getUserConversations(User $user)
    {
        return $user->getUserConversations();
    }

    public function getNotBefriendedUsers(User $user)
    {
        return $user->getNotBefriendedUsers();
    }

    public function getFriendRequestsReceived(User $user)
    {

        return $user->getFriendRequestsReceived();
    }

    public function getFriendRequestsSent(User $user)
    {

        return $user->getFriendRequestsSent();
    }

    public function getFriends(User $user)
    {
        return $user->getFriends();
    }

    public function getUsersWithLimit(int $limit)
    {
        return User::limit($limit)->get();
    }

    public function checkIfUserBelongsToConversation(User $user, Conversation $conversation): bool
    {
        return $user->conversations()->where("conversation_id", $conversation->id)->exists();
    }
}
