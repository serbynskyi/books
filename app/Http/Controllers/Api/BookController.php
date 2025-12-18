<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookShortResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index()
    {
        return BookShortResource::collection(
            Book::paginate()
        );
    }

    public function show(Book $book)
    {
        return new BookResource($book);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'authors' => 'required|array|min:1',
            'authors.*.author' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'genres' => 'required|array|min:1',
            'genres.*.genre' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'edition' => 'nullable|integer',
            'publisher' => 'required|string|max:255',
            'published_at' => 'required|date',
            'format' => 'required|string|max:255',
            'pages' => 'required|integer|min:1',
            'country' => 'required|string|max:255',
            'isbn' => 'required|string|max:50|unique:books,isbn',
        ]);

        return DB::transaction(function () use ($data, $request) {

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
                'publisher' => $request->publisher,
            ]);

            $country = Country::firstOrCreate([
                'country' => $request->country,
            ]);

            $book = Book::create([
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

            return response(new BookResource($book), 201);
        });
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'isbn' => 'sometimes|required|string|max:50|unique:books,isbn,' . $book->id,
            'published_at' => 'nullable|date',
        ]);

        $book->update($data);

        return new BookResource($book);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->noContent();
    }
}
