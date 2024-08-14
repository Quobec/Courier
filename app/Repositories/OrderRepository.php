<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(bool $is_paid, int $price, string $sessionId, string $userId): void
    {
        Order::create([
            "is_paid"=> $is_paid,
            "total_price"=> $price,
            "session_id"=> $sessionId,
            "user_id"=> $userId
        ]);
    }

    public function getBySessionId(string $sessionId): Order
    {
        return Order::where('session_id', $sessionId)->first();
    }

    public function increaseUserFriendLimit(Order $order){
        $user = $order->user()->get()->first();
        $user->friend_slots += 10;
        $user->save();
    }

    public function changeStatus(Order $order, bool $status): void
    {
        $order->is_paid = $status;
        $order->save(); 
    }
}
