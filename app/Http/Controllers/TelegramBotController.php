<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function handle()
    {
        $updates = Telegram::commandsHandler(true);
        return response('OK estas aqui');
    }
}
