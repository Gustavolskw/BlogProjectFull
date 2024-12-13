<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'thread' => $this->thread_id,
            'user' => $this->user_id,
            'comment' => $this->comment,
            'likes' => $this->like_count,
            'dataComentario' => $this->data_comment
        ];
    }
}
