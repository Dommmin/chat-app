<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Message;
use App\Models\Read;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReadFactory extends Factory
{
    protected $model = Read::class;

    public function definition(): array
    {
        return [
            'read_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'message_id' => Message::factory(),
            'user_id' => User::factory(),
        ];
    }
}
