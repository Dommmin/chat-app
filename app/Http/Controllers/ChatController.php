<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Repository\ChatRepository;
use App\Repository\MessageRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function __construct(private readonly ChatRepository $chatRepository, private readonly MessageRepository $messageRepository)
    {
    }

    public function index()
    {
        $chats = $this->chatRepository->getPaginatedChats();

        return Inertia::render('Chats/Index', [
            'chats' => ChatResource::collection($chats),
        ]);
    }

    public function show(Chat $chat, Request $request)
    {
        if ($request->user()->cant('view', $chat)) {
            abort(403);
        }

        $chats = $this->chatRepository->getPaginatedChats();
        $messages = $this->messageRepository->getPaginatedMessages($chat);
        $attachments = $this->messageRepository->getPaginatedAttachments($chat);

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
