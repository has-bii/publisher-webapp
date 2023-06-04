<?php

namespace App\Http\Controllers\API;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function fetch(Request $request)
    {

        $id = Auth::id();
        $limit = $request->input('limit', 100);

        $user_id = $request->input('user_id');

        if ($user_id) {

            $chats = Chat::with(['participants' => function ($query) use ($id) {
                $query->where('user_id', '!=', $id);
            }])
                ->whereHas('participants', function ($query) use ($id) {
                    $query->where('user_id', $id);
                })
                ->whereHas('participants', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })->first();

            return ResponseFormatter::success($chats, 'Fetch success');
        }

        $chats = Chat::with(['last_message.sender', 'participants' => function ($query) use ($id) {
            $query->with('user')->where('user_id', '!=', $id);
        }])
            ->whereHas('participants', function ($query) use ($id) {
                $query->where('user_id', $id);
            })->orderBy('updated_at', 'desc');


        return ResponseFormatter::success($chats->paginate($limit), 'Fetch success');
    }
}
