<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateChatRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateMessageRequest;
use App\Models\Participant;

class MessageController extends Controller
{
    public function fetch(Request $request)
    {

        $chat_id = $request->input('chat_id');

        $id = Auth::id();

        $messages = Message::with('sender')->where('chat_id', $chat_id)->orderBy('created_at', 'desc')->get();


        return ResponseFormatter::success($messages, 'Fetch success');
    }

    public function new_message(CreateMessageRequest $request)
    {
        if ($request->chat_id) {

            try {

                $message = Message::create([
                    'message' => $request->message,
                    'chat_id' => $request->chat_id,
                    'sender_id' => $request->sender_id,
                ]);

                if (!$message) {
                    throw new Exception('message not created');
                }

                $chat = Chat::find($request->chat_id);

                $chat->update([
                    'last_message_id' => $message->id
                ]);

                return ResponseFormatter::success($message, 'Message created');
            } catch (Exception $error) {
                return ResponseFormatter::error($error->getMessage());
            }
        }

        return ResponseFormatter::error('No chat id selected');
    }

    public function new_chat(CreateChatRequest $request)
    {

        try {

            $chat = Chat::create();

            $chat_id = $chat->id;

            $receiver_id = $request->receiver_id;
            $sender_id = Auth::id();

            $sender = Participant::create([
                'user_id' => $sender_id,
                'chat_id' => $chat_id,
            ]);

            $receiver = Participant::create([
                'user_id' => $receiver_id,
                'chat_id' => $chat_id,
            ]);

            if (!$receiver && !$sender) {
                $chat->delete();

                throw new Exception('Participants not created');
            }

            $message = Message::create([
                'message' => $request->message,
                'chat_id' => $chat_id,
                'sender_id' => $request->sender_id,
            ]);

            if (!$message) {
                throw new Exception('message not created');
            }

            $chat->update([
                'last_message_id' => $message->id
            ]);

            return ResponseFormatter::success($message, 'Message created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }
}
