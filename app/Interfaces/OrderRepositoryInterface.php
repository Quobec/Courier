<?php

namespace App\Interfaces;
use App\Models\Order;

interface OrderRepositoryInterface
{
    public function create(bool $is_paid, int $price, string $sessionId, string $userId);

    public function getBySessionId(string $sessionId);

    public function increaseUserFriendLimit(Order $order);

    public function changeStatus(Order $order, bool $status);
}
