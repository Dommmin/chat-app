<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\MessageStoreRequest;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;

class MessageController extends Controller
{
    public function index(Chat $chat)
    {
        $messages = Message::query()
            ->whereChatId($chat->id)
            ->latest()
            ->simplePaginate(50)
            ->reverse();

        return MessageResource::collection($messages);
    }

    public function store(MessageStoreRequest $request, Chat $chat)
    {
        $validatedData = $request->validated();
        $validatedData['from_id'] = $request->user()->id;

        $chat->messages()->create($validatedData);

        event(new MessageSent());

        return to_route('chats.show', $chat->id);
    }
}
