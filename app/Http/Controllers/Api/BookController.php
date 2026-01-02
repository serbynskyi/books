<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookShortResource;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        return BookShortResource::collection(
            Book::query()
                ->sort($request->get('sort'), $request->get('direction'))
                ->search($request->get('search'))
                ->paginate(20)
        );
    }

    public function show(Book $book)
    {
        return new BookResource($book);
    }

    public function store(
        StoreBookRequest $request,
        BookService $service
    ) {
        $book = $service->create($request->validated());

        return response(new BookResource($book), 201);
    }

    public function update(
        UpdateBookRequest $request,
        Book $book,
        BookService $service
    ) {
        return new BookResource($service->update($book, $request->validated()));
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->noContent();
    }
}
