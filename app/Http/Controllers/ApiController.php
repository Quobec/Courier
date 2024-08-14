<?php

namespace App\Http\Controllers;

use App\Events\NewMessageEvent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    public function register(Request $request){
        $validated = $request->validate([
            "name" => "required",
            "email" => "email|required|unique:users,email",
            "password" => "confirmed|required|min:2|max:40",
        ]);
    
        $user = User::create($validated);

        return [
            'user' => $user,
        ];
    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        return explode('|', $user->createToken($request->device_name)->plainTextToken)[1];
    }

    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully'
        ]);
    }

    public function addFriend(Request $request, User $user){
        
        $invitingUser = $request->user();
        $invitedUser = $user;
        
        if($invitingUser->friends()->get()->count() < $invitingUser->friend_slots){
            if (
                $invitingUser->friends()->get()->contains("id", $invitedUser->id) ||
                $invitedUser->friends()->get()->contains("id", $invitingUser->id)
            ) {
                return [
                    "status"=> "failed",
                    "message"=> "You are already friends or there is already a pending friend request."
                ];
            } else {
                $invitingUser->friends()->attach($invitedUser->id);
                return [
                    "status"=> "success",
                    "message"=> "Friend request sent"
                ];
            }
        } else {
            return [
                "status"=> "failed",
                "message"=> "Friend limit reached. You can extend it in settings."
            ];
        }
    }

    public function updateConversation(Request $request,Conversation $conversation){
        
        $loggedInUser = $request->user();
        $friends = $request->get("friends");
        
        if(in_array($loggedInUser->id, $friends)){
            unset($friends[array_search($loggedInUser->id, $friends)]);
        }

        if($conversation->users()->get()->contains($loggedInUser)){
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
    
            return ["status"=> "success","message"=> "Conversation updated."];
        } else {
            return ["status"=> "failed","message"=> 'This user does not have access to this conversation.'];
        }
    }

    public function sendMessage(Request $request){
        $validated = $request->validate([
            "content"=> "required",
            "conversation_id"=> "required"
        ]);

        $conversation = Conversation::find($validated['conversation_id']);
        if($conversation == null){
            return ['status'=> 'failed','message'=> 'Conversation with this id doesnt exist.'];
        }
        
        $conversationUsers = Conversation::find($validated['conversation_id'])->users()->get() ;
        if( $conversationUsers->contains($request->user()) ){
            $message = Message::create([
                'content' => $validated['content'],
                'conversation_id' => $validated['conversation_id'],
                'user_id' => $request->user()->id,
            ]);
    
            broadcast(new NewMessageEvent($validated['content'], $request->user()->name, "chat" . $validated['conversation_id']));
    
            return $message;
        } else {
            return ["status"=> "failed","message"=> 'This user does not have access to this conversation.'];
        }
        
    }
}