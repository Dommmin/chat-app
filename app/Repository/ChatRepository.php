<?php

declare(strict_types=1);

namespace App\Repository;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class ChatRepository
{
    public function getChats()
    {
        return Chat::select(['chats.name', 'chats.id', 'chats.is_group'])
            ->joinSub(
                Message::select('chat_id', DB::raw('MAX(created_at) as latest_message_date'))
                    ->groupBy('chat_id'),
                'latest_messages',
                'chats.id',
                '=',
                'latest_messages.chat_id'
            )
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('chat_participants')
                    ->whereColumn('chats.id', 'chat_participants.chat_id')
                    ->where('chat_participants.user_id', auth()->id());
            })
            ->with(['users', 'messages' => function ($query) {
                $query->latest()->limit(1)->with(['reads' => function ($query) {
                    $query->latest()->limit(1);
                }]);
            }])
            ->orderByDesc('latest_messages.latest_message_date')
            ->simplePaginate(10, ['*'], 'page_chats');
    }
}
