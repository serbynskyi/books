<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdateBookValidator
{
    public function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'authors' => ['array', 'min:1'],
            'authors.*.author' => ['string', 'max:255'],
            'title' => ['string', 'max:255'],
            'genres' => ['array', 'min:1'],
            'genres.*.genre' => ['string', 'max:255'],
            'description' => ['string', 'max:255'],
            'edition' => ['nullable', 'integer'],
            'publisher' => ['string', 'max:255'],
            'published_at' => ['date'],
            'format' => ['string', 'max:255'],
            'pages' => ['integer', 'min:1'],
            'country' => ['string', 'max:255'],
            'isbn' => ['string', 'max:50', 'unique:books,isbn'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
