<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendMessageRequest;
use App\Models\MessageReceiver;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function my_message(Request $request)
    {
        $limit = $request->input('limit', 100);

        $messages = Message::where('sender_id', Auth::id())->with(['message_receiver', 'user']);

        return ResponseFormatter::success($messages->paginate($limit), 'Messages found');
    }

    public function send_message(SendMessageRequest $request)
    {
        try {

            $message = Message::create([
                'body' => $request->body,
                'sender_id' => Auth::id(),
                'parent_id' => isset($request->parent_id) ? $request->parent_id : null,
            ]);

            $messageReceiver = MessageReceiver::create([
                'receiver_id' => $request->receiver_id,
                'message_id' => $message->id
            ]);

            if (!$message && !$messageReceiver) {
                throw new Exception('Message not sent');
            }

            return ResponseFormatter::success($message, 'Message sent');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }

    public function get_message(Request $request)
    {
        $limit = $request->input('limit', 100);

        $messages = MessageReceiver::with('message')->where('receiver_id', Auth::id());

        return ResponseFormatter::success($messages->paginate($limit), 'Messages found');
    }

    public function open_message(Request $request)
    {
        $limit = $request->input('limit', 100);
        $sender_id = $request->input('sender_id');

        $message = Message::where('sender_id', $sender_id)->where('parent_id', null)->with('message_parent');

        return ResponseFormatter::success($message->paginate($limit), 'Messages found');
    }
}
