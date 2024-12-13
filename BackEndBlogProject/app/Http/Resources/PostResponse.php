<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResponse extends JsonResource
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
            'usuario' => $this->user->name,
            'usuarioId' => $this->user->id,
            'thread' => $this->thread->id,
            'titulo' => $this->title,
            'conteudo' => $this->content,
            'dataDoPost' => $this->data_post,
            'imagens' => $this->postImages->pluck('image_url')
        ];
    }
}
