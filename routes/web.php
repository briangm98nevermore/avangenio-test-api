<?php

use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/send-message', function () {
    $chatId = '817775788'; // Replace with your chat ID
    $message = 'Te amo mucho...Nunca lo olvidessssssssssssssssss';

    Telegram::sendMessage([
    'chat_id' => $chatId,
    'text' => $message,
    ]);

    return 'Message sent to Telegram!';
    });

    Route::get('/get-updates', function () {
        $updates = Telegram::getUpdates();
        return $updates;
        });
