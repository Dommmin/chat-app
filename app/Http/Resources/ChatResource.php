<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Chat
 */
class ChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?: '',
            'is_group' => (bool) $this->is_group,
            'latest_message' => $this->messages->first(),
            //            'latest_message' => $this->is_group ? $this->latestMessage : new LatestMessageResource($this->latestMessage),
            //            'users' => $this->is_group ? $this->users : [],
            'users' => $this->users,
            'user' => $this->users->filter(fn ($user) => $user->id !== auth()->id())->first(),
            'messages' => $request->is('api/chats/*') ? $this->messages : [],
        ];
    }
}
