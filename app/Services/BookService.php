<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BookService
{
    public function create(array $data): Book
    {
        return DB::transaction(function () use ($data) {

            $book = Book::create(Arr::except($data, ['author_ids', 'genre_ids']));

            $book->authors()->sync($data['author_ids']);
            $book->genres()->sync($data['genre_ids']);

            return $book;
        });
    }

    public function update(Book $book, array $data): Book
    {
        return DB::transaction(function () use ($book, $data) {
            $book->fill(Arr::except($data, ['author_ids', 'genre_ids']));
            $book->save();

            isset($data['author_ids']) && $book->authors()->sync($data['author_ids']);
            isset ($data['genre_ids']) && $book->genres()->sync($data['genre_ids']);

            return $book;
        });
    }
}
