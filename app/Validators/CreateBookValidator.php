<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateBookValidator
{
    public function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'authors' => ['required', 'array', 'min:1'],
            'authors.*.author' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'genres' => ['required', 'array', 'min:1'],
            'genres.*.genre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'edition' => ['nullable', 'integer'],
            'publisher' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'date'],
            'format' => ['required', 'string', 'max:255'],
            'pages' => ['required', 'integer', 'min:1'],
            'country' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:50', 'unique:books,isbn'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
