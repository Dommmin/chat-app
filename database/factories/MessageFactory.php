<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'body' => $this->faker->word(),
            'attachment' => $this->faker->word(),
            'is_read' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'chat_id' => Chat::factory(),
            'from_id' => User::factory(),
        ];
    }
}
