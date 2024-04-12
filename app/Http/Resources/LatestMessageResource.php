<?php

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin Message
 */
class LatestMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'from_id' => $this->from_id,
            'body' => Str::limit($this->body, 30),
            'attachment' => $this->attachment,
            'chat_id' => $this->chat_id,
            'created_at' => $this->created_at,
            'is_read' => $this->from_id == auth()->id() ? true : $this->reads->filter(fn ($read) => $read->user_id == auth()->id())->isNotEmpty(),
            //            'read_at' => $this->reads->filter(fn ($read) => $read->user_id == auth()->id())->first()?->created_at ?? null,
        ];

    }
}
