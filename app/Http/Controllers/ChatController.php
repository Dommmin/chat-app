<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use App\Repository\ChatRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    public function index()
    {
        $chats = $this->chatRepository->getChats();

        return Inertia::render('Chats/Index', [
            'chats' => ChatResource::collection($chats),
        ]);
    }

    public function show(Chat $chat, Request $request)
    {
        if ($request->user()->cant('view', $chat)) {
            abort(403);
        }

        $chats = $this->chatRepository->getChats();

        $messages = Message::whereChatId($chat->id)->latest()->simplePaginate(20)->reverse();

        $attachments = Message::select('attachment')
            ->whereChatId($chat->id)
            ->whereNotNull('attachment')
            ->latest()
            ->simplePaginate(10);

        $user = $chat->users()->where('user_id', '!=', auth()->id())->first();

        return Inertia::render('Chats/Show', [
            'id' => $chat->id,
            'user' => $user,
            'chats' => ChatResource::collection($chats),
            'messages' => MessageResource::collection($messages),
            'attachments' => $attachments,
        ]);
    }
}
