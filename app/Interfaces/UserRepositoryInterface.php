<?php

namespace App\Interfaces;

use App\Models\Conversation;
use App\Models\User;

interface UserRepositoryInterface
{

    public function create(array $data);

    public function getUserByEmail(string $email);

    public function changeTfaCode(User $user, string $code);

    public function changeTfaState(User $user, bool $state);

    public function changeUserPhoneNumber(User $user, string $phoneNumber);

    public function inviteFriend(User $invitingUser, User $invitedUser);

    public function acceptFriendInvite(User $invitedUser, User $invitingUser);

    public function rejectFriendInvite(User $invitedUser, User $invitingUser);

    public function cancelFriendInvite(User $invitedUser, User $invitingUser);
    public function getUserConversations(User $user);

    public function getNotBefriendedUsers(User $user);

    public function getFriendRequestsReceived(User $user);

    public function getFriendRequestsSent(User $user);

    public function getFriends(User $user);

    public function getUsersWithLimit(int $limit);

    public function checkIfUserBelongsToConversation(User $user, Conversation $conversation);
}
