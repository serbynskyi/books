<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'author_ids' => ['required', 'array', 'min:1'],
            'author_ids.*' => ['integer', 'exists:authors,id'],
            'title' => ['required', 'string', 'max:255'],
            'genre_ids' => ['required', 'array', 'min:1'],
            'genre_ids.*' => ['integer', 'exists:genres,id'],
            'description' => ['required', 'string'],
            'edition' => ['nullable', 'integer'],
            'publisher_id' => ['required', 'integer', 'exists:publishers,id'],
            'published_at' => ['required', 'date'],
            'format' => ['required', 'string', 'max:255'],
            'pages' => ['required', 'integer', 'min:1'],
            'country_code' => ['required', 'string', 'size:2', 'exists:countries,code'],
            'isbn' => ['required', 'string', 'max:50', 'unique:books,isbn'],
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
