<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Chat;
use App\Models\Message;

class MessageRepository
{
    public function getPaginatedMessages(Chat $chat)
    {
        $results = Message::query()
            ->where('chat_id', $chat->id)
            ->latest('created_at')
            ->simplePaginate(20, ['*'], 'page_messages');

        $results->setCollection(
            $results->getCollection()
                ->sortBy(fn (Message $message) => $message->created_at)
                ->values()
        );

        return $results;
    }

    public function getPaginatedAttachments(Chat $chat)
    {
        return Message::query()
            ->select('attachment')
            ->where('chat_id', $chat->id)
            ->whereNotNull('attachment')
            ->latest()
            ->simplePaginate(10, ['*'], 'page_attachments');
    }
}
