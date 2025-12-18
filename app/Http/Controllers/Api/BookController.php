<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookShortResource;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;

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

    public function store(
        Request $request,
        BookService $service
    ) {
        $book = $service->create($request->all());

        return response(new BookResource($book), 201);
    }

    public function update(
        Request $request,
        Book $book,
        BookService $service
    ) {
        return new BookResource($service->update($book, $request->all()));
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->noContent();
    }
}
