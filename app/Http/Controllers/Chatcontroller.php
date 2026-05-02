use App\Services\MinimaxService;

public function chat(Request $request, MinimaxService $minimax)
{
    $result = $minimax->chat($request->message);

    return response()->json($result);
}