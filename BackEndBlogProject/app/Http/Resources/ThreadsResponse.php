<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThreadsResponse extends JsonResource
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
            'titulo' => $this->title,
            'descricao' => $this->description,
            'imagem' => $this->thread_img,
            'likes' => $this->like_count,
            'dataCriacao' => $this->created_at,
            'autor' => $this->user->name,
        ];
    }
}
