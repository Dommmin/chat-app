<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::select(['chats.name', 'chats.id', 'chats.is_group'])
            ->selectSub(function ($query): void {
                $query->select('created_at')
                    ->from('messages')
                    ->whereColumn('messages.chat_id', 'chats.id')
                    ->orderByDesc('created_at')
                    ->limit(1);
            }, 'latest_message_date')
            ->whereExists(function ($query): void {
                $query->select(DB::raw(1))
                    ->from('chat_participants')
                    ->whereColumn('chats.id', 'chat_participants.chat_id')
                    ->where('chat_participants.user_id', auth()->id());
            })
            ->with([
                'latestMessage:id,from_id,body,attachment,chat_id,created_at',
                'latestMessage.reads' => function ($query): void {
                    $query->where('user_id', auth()->id());
                },
                'users',
            ])
            ->orderByDesc('latest_message_date')
            ->simplePaginate(10);

        return Inertia::render('Chats/Index', [
            'chats' => ChatResource::collection($chats),
        ]);
    }

    public function show(Chat $chat, Request $request)
    {
        $chats = Chat::select(['chats.name', 'chats.id', 'chats.is_group'])
            ->selectSub(function ($query): void {
                $query->select('created_at')
                    ->from('messages')
                    ->whereColumn('messages.chat_id', 'chats.id')
                    ->orderByDesc('created_at')
                    ->limit(1);
            }, 'latest_message_date')
            ->whereExists(function ($query): void {
                $query->select(DB::raw(1))
                    ->from('chat_participants')
                    ->whereColumn('chats.id', 'chat_participants.chat_id')
                    ->where('chat_participants.user_id', auth()->id());
            })
            ->with([
                'latestMessage:id,from_id,body,attachment,chat_id,created_at',
                'latestMessage.reads' => function ($query): void {
                    $query->where('user_id', auth()->id());
                },
                'users',
            ])
            ->orderByDesc('latest_message_date')
            ->simplePaginate(10, ['*'], 'page_chats');

        $messages = Message::whereChatId($chat->id)->latest()->simplePaginate(20)->reverse();

        $attachments = Message::select('attachment')->whereChatId($chat->id)->whereNotNull('attachment')->latest()->simplePaginate(10);

        $user = $chat->users()
            ->where('user_id', '!=', auth()->id())
            ->first();

        return Inertia::render('Chats/Show', [
            'id' => $chat->id,
            'user' => $user,
            'chats' => ChatResource::collection($chats),
            'messages' => MessageResource::collection($messages),
            'attachments' => $attachments,
        ]);
    }
}
