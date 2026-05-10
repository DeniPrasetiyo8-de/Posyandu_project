<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MinimaxService;

class ChatController extends Controller
{
    public function chat(Request $request, MinimaxService $minimax)
    {
        $result = $minimax->chat($request->message);

        return response()->json($result);
    }
}
