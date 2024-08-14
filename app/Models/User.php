<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements Auth
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'phone_number',
        'tfa_state',
        'tfa_code',
        'google_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class);
    }

    public function getUserConversations()
    {
        return $this->conversations()->get();
    }

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friend_user', 'user_id', 'friend_id')->withPivot('confirmed');
    }

    public function befriendedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friend_user', 'friend_id', 'user_id')->withPivot('confirmed');
    }

    public function getFriends(): Collection
    {
        return $this->friends()->wherePivot('confirmed', true)->get()->merge($this->befriendedBy()->wherePivot('confirmed', true)->get());
    }

    public function getFriendRequestsReceived(): Collection
    {
        return $this->friends()->wherePivot('confirmed', false)->where('friend_id', $this->id)->get()
            ->merge($this->befriendedBy()->wherePivot('confirmed', false)->where('friend_id', $this->id)->get());
    }

    public function getFriendRequestsSent(): Collection
    {
        return $this->friends()->wherePivot('confirmed', false)->where('user_id', $this->id)->get()
            ->merge($this->befriendedBy()->wherePivot('confirmed', false)->where('user_id', $this->id)->get());
    }

    public function getNotBefriendedUsers(): Collection
    {

        // Ids of users that this user sent friend request to
        $usersNotToDisplay = $this->friends()->wherePivot('confirmed', false)->get()->pluck('id')->toArray();

        // Ids of users that sent friend request to this user 
        foreach ($this->befriendedBy()->wherePivot('confirmed', false)->get()->pluck('id')->toArray() as $key) {
            array_push($usersNotToDisplay, $key);
        }

        // Ids of users that are already friends
        foreach ($this->getFriends()->pluck('id')->toArray() as $key) {
            array_push($usersNotToDisplay, $key);
        }

        // Id of this user
        array_push($usersNotToDisplay, $this->id);

        return User::whereNotIn("id", $usersNotToDisplay)->limit(20)->get();
    }

    public function belongsToConversation(Conversation $conversation): bool
    {
        return $conversation->users()->where("user_id", $this->id)->exists();
    }
}
