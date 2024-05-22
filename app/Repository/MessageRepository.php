<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Pagination\Paginator;

class MessageRepository
{
    public function getPaginatedMessages(Chat $chat)
    {
        return tap(Message::query()
            ->where('chat_id', $chat->id)
            ->latest()
            ->simplePaginate(30, ['*'], 'page_messages'), function (Paginator $paginator): void {
                $paginator->setCollection(
                    $paginator->getCollection()->reverse()->values()
                );
            });
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
