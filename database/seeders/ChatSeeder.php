<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $id = 1;

        $user1 = User::find($id);
        $users = User::where('id', '<>', $id)->get();

        foreach ($users as $user) {
            $chat = Chat::factory()->create();
            $chat->users()->sync([$user1->id, $user->id]);
        }
    }
}
