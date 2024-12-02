<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    protected $telegram;

    public function sendMessage($message, $chat_id)
    {
        try {
            $telegram = new Api();
            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $message,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim pesan ke Telegram', ['error' => $e->getMessage()]);
        }
    }

    public function sendMessageWithLinkButton($message, $buttonText, $url, $chat_id)
    {
        try {
            $telegram = new Api();
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => $buttonText, 'url' => $url]
                    ]
                ]
            ];

            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $message,
                'reply_markup' => json_encode($keyboard)
            ]);
            Log::info('Berhasil mengirim pesan ke Telegram');
        } catch (\Exception $e) {
            Log::error('Gagal mengirim pesan ke Telegram', ['error' => $e->getMessage()]);
        }
    }

    public function sendMessageWithTwoButtons($message, $button1Text, $button1Url, $button2Text, $button2Url, $chat_id)
    {
        try {
            $telegram = new Api();
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => $button1Text, 'url' => $button1Url],
                        ['text' => $button2Text, 'url' => $button2Url]
                    ]
                ]
            ];

            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $message,
                'reply_markup' => json_encode($keyboard),
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim pesan ke Telegram', ['error' => $e->getMessage()]);
        }
    }

    public function sendMessageWithThreeButtons($message, $button1Text, $button1Url, $button2Text, $button2Url, $button3Text, $button3Url, $chat_id)
    {
        try {
            $telegram = new Api();
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => $button1Text, 'url' => $button1Url],
                        ['text' => $button2Text, 'url' => $button2Url],
                        ['text' => $button3Text, 'url' => $button3Url]
                    ]
                ]
            ];

            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $message,
                'reply_markup' => json_encode($keyboard),
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim pesan ke Telegram', ['error' => $e->getMessage()]);
        }
    }
}
