<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Book;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Publisher;
use App\Validators\CreateBookValidator;
use App\Validators\UpdateBookValidator;
use Illuminate\Support\Facades\DB;

class BookService
{
    public function __construct(
        private readonly CreateBookValidator $createBookValidator,
        private readonly UpdateBookValidator $updateBookValidator
    ) {}

    public function create(array $requestData): Book
    {
        $data = $this->createBookValidator->validate($requestData);

        return DB::transaction(function () use ($data) {

            $authorsPayload = [];
            foreach ($data['authors'] as $authorData) {
                $author = Author::firstOrCreate(
                    ['author' => $authorData['author']]
                );
                $authorsPayload[] = [
                    'id' => $author->id,
                    'author' => $author->author,
                ];
            }

            $genresPayload = [];
            foreach ($data['genres'] as $genreData) {
                $genre = Genre::firstOrCreate(
                    ['genre' => $genreData['genre']]
                );
                $genresPayload[] = [
                    'id' => $genre->id,
                    'genre' => $genre->genre,
                ];
            }

            $publisher = Publisher::firstOrCreate([
                'publisher' => $data['publisher'],
            ]);

            $country = Country::firstOrCreate([
                'country' => $data['country'],
            ]);

            return Book::create([
                'authors' => $authorsPayload,
                'title' => $data['title'],
                'genres' => $genresPayload,
                'description' => $data['description'],
                'edition' => $data['edition'] ?? null,
                'publisher_id' => $publisher->id,
                'published_at' => $data['published_at'],
                'format' => $data['format'],
                'pages' => $data['pages'],
                'country_id' => $country->id,
                'isbn' => $data['isbn'],
            ]);
        });
    }

    public function update(Book $book, array $requestData): Book
    {
        $data = $this->updateBookValidator->validate($requestData);

        return DB::transaction(function () use ($book, $data) {
            if (isset($data['authors'])) {
                $authorsPayload = [];
                foreach ($data['authors'] as $authorData) {
                    $author = Author::firstOrCreate(
                        ['author' => $authorData['author']]
                    );
                    $authorsPayload[] = [
                        'id' => $author->id,
                        'author' => $author->author,
                    ];
                }
                $book->authors = $authorsPayload;
                unset($data['authors']);
            }

            if (isset($data['genres'])) {
                $genresPayload = [];
                foreach ($data['genres'] as $genreData) {
                    $genre = Genre::firstOrCreate(
                        ['genre' => $genreData['genre']]
                    );
                    $genresPayload[] = [
                        'id' => $genre->id,
                        'genre' => $genre->genre,
                    ];
                }
                $book->genres = $genresPayload;
                unset($data['genres']);
            }

            if (isset($data['publisher'])) {
                $publisher = Publisher::firstOrCreate([
                    'publisher' => $data['publisher'],
                ]);
                $book->publisher_id = $publisher->id;
                unset($data['publisher']);
            }

            if (isset($data['country'])) {
                $country = Country::firstOrCreate([
                    'country' => $data['country'],
                ]);
                $book->country_id = $country->id;
                unset($data['country']);
            }

            $book->fill($data);
            $book->save();

            return $book;
        });
    }

    public function createOrUpdateByIsbn(array $data): Book
    {
        $book = Book::where('isbn', $data['isbn'])->first();

        return $book
            ? $this->update($book, $data)
            : $this->create($data);
    }
}
