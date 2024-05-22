<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Database\Seeder;

class ReadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $latestMessageIds = Chat::selectSub(function ($query): void {
            $query->select('id')
                ->from('messages')
                ->whereColumn('messages.chat_id', 'chats.id')
                ->latest('created_at')
                ->limit(1);
        }, 'latest_message_id')
            ->pluck('latest_message_id');

        $count = count($latestMessageIds);

        Message::query()
            ->select('id', 'to_id')
            ->whereIn('id', $latestMessageIds)
            ->inRandomOrder()
            ->limit((int) ($count / 3))
            ->chunk(200, function ($messages): void {
                foreach ($messages as $message) {
                    $message->reads()->create([
                        'read_at' => now(),
                        'user_id' => $message->to_id,
                    ]);
                }
            });
    }
}
