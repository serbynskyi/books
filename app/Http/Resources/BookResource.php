<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'authors' => AuthorShortResource::collection($this->authors),
            'title' => $this->title,
            'genres' => GenreShortResource::collection($this->genres),
            'description' => $this->description,
            'edition' => $this->edition,
            'publisher' => $this->publisher->publisher,
            'published_at' => $this->published_at?->toDateString(),
            'format' => $this->format,
            'pages' => $this->pages,
            'country' => $this->country->country,
            'isbn' => $this->isbn,
        ];
    }
}
