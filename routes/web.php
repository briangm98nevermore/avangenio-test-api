<?php

use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/main', function () {
    return 'Esto es un mensaje de Brian González XD';
});


Route::get('/send-message', function () {
    $chatId = '925445465'; // Replace with your chat ID
    $message = 'this is a test message';

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
