<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'author_ids' => ['array', 'min:1'],
            'author_ids.*' => ['integer', 'exists:authors,id'],
            'title' => ['string', 'max:255'],
            'genre_ids' => ['array', 'min:1'],
            'genre_ids.*' => ['integer', 'exists:genres,id'],
            'description' => ['string'],
            'edition' => ['nullable', 'integer'],
            'publisher_id' => ['integer', 'exists:publishers,id'],
            'published_at' => ['date'],
            'format' => ['string', 'max:255'],
            'pages' => ['integer', 'min:1'],
            'country_id' => ['integer', 'exists:countries,id'],
            'isbn' => ['string', 'max:50', 'unique:books,isbn'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('edition') && $this->input('edition') === '') {
            $this->merge([
                'edition' => null,
            ]);
        }
    }
}
