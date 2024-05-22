<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\MessageStoreRequest;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function store(MessageStoreRequest $request, Chat $chat)
    {
        if ($request->user()->cant('view', $chat)) {
            abort(403);
        }

        $validatedData = $request->validated();
        $validatedData['from_id'] = $request->user()->id;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $ext = $file->getClientOriginalExtension();
            $originalName = $file->getClientOriginalName();
            $fileName = $originalName . uniqid() . '.' . $ext;
            $path = 'attachments/' . $chat->id;

            Storage::disk('public')->putFileAs($path, $file, $fileName);

            $validatedData['attachment'] = Storage::url($path . '/' . $fileName);
        }

        $chat->messages()->create($validatedData);

        event(new MessageSent());

        return to_route('chats.show', $chat->id)->with('success', 'Message sent');
    }

    public function destroy(Chat $chat, Message $message, Request $request)
    {
        if ($request->user()->cant('view', $chat)) {
            abort(403);
        }

        if ($request->user()->cant('delete', $message)) {
            abort(403);
        }

        $message->update(['deleted_at' => now()]);

        return to_route('chats.show', $message->chat_id)->with('success', 'Message deleted');
    }
}
