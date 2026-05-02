<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MinimaxService
{
    public function chat($message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('MINIMAX_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post(env('MINIMAX_BASE_URL') . '/text/chatcompletion_v2', [
            "model" => "abab6.5-chat", // atau M2.7 kalau tersedia di akun kamu
            "messages" => [
                [
                    "role" => "user",
                    "content" => $message
                ]
            ]
        ]);

        return $response->json();
    }
}