<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class,'index'])->name('home');

Route::group([
    "prefix"=> "auth/google",
    "middleware" => "guest"
], function () {

    Route::get('/redirect', [GoogleAuthController::class,'redirect'])->name('google.redirect');

    Route::get('/callback', [GoogleAuthController::class,'callback'])->name('google.callback');
    
    Route::get('/login', [GoogleAuthController::class,'login'])->name('google.login');
});

Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');

Route::post('/register/store', [AuthController::class, 'store'])->name('register.store')->middleware('guest');

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');

Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate')->middleware('guest');

Route::post('/login/tfa', [AuthController::class, 'checkTFA'])->name('login.tfa')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::group([
    'prefix'=> 'friends',
    'middleware'=> 'auth'
], function () {
    Route::post('/{user}/add', [FriendsController::class, 'store'])->name('friend.add');

    Route::patch('/{user}/accept', [FriendsController::class, 'accept'])->name('friend.accept');
    
    Route::delete('/{user}/reject', [FriendsController::class, 'reject'])->name('friend.reject');
    
    Route::delete('/{user}/cancel', [FriendsController::class, 'cancel'])->name('friend.cancel');
});

Route::group([
    'prefix'=> 'conversations',
    'middleware'=> 'auth'
], function () {
    Route::post('/{conversation}/send', [MessageController::class,'store'])->name('conversations.message.store');

    Route::get('/{conversation}', [MessageController::class,'show'])->name('conversations.message.show');
});

Route::group([
    'prefix'=> 'conversation',
    'middleware'=> 'auth'
], function () {
    Route::get('/create', [ConversationController::class, 'create'])->name('conversation.create');

    Route::post('/store', [ConversationController::class, 'store'])->name('conversation.store');

    Route::get('/{conversation}/add', [ConversationController::class, 'add'])->name('conversation.add');

    Route::post('/{conversation}/update', [ConversationController::class, 'update'])->name('conversation.update');
});

Route::group([
    'prefix'=> 'profile',
    'middleware'=> 'auth'
], function () {
    Route::get('/{user}/settings', [ProfileController::class,'show'])->name('profile.settings');

    Route::patch('/{user}/tfa', [ProfileController::class,'toggleTFA'])->name('profile.settings.toggleTFA');
});

Route::group([
    'prefix'=> 'payment',
    'middleware'=> 'auth'
], function () {
    Route::get('/checkout', [PaymentController::class,'index'])->name('checkout');

    Route::get('/success', [PaymentController::class,'success'])->name('payment.success');

    Route::get('/cancel', [PaymentController::class,'cancel'])->name('payment.cancel');

    Route::post('/webhook', [PaymentController::class,'webhook'])->withoutMiddleware('auth');
});
