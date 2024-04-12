<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Chat::chunk(200, function ($chats) {
            $messages = [];

            foreach ($chats as $chat) {
                for ($i = 0; $i < rand(50, 1000); $i++) {
                    $messages[] = [
                        'uuid' => Str::uuid(),
                        'body' => fake()->sentence(),
                        'chat_id' => $chat->id,
                        'from_id' => $chat->load('users')->users->random()->id,
                        'created_at' => Carbon::now()->subMinutes(rand(1, 30 * 24 * 60)),
                        'updated_at' => now(),
                    ];
                }
            }

            shuffle($messages);

            Message::insert($messages);
        });
    }
}
