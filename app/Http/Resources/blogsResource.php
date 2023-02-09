<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class blogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'author' => $this->author,
            'author_id' => $this->author_id,
            'image' => $this->image,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at->format('m/d/Y'),
            'updated_at' => $this->updated_at->format('m/d/Y'),
        ];
    }
}
