<?php

namespace App\Services;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use SplFileObject;

class ImportService
{
    public function __construct(
        private readonly BookService $bookService
    ) {
    }

    public function import(UploadedFile $file): array
    {
        $result = [
            'imported' => 0,
            'failed' => 0,
            'errors' => []
        ];

        $csv = new SplFileObject($file->getRealPath());
        $csv->setFlags(SplFileObject::READ_CSV);

        $csv->rewind();
        $csv->fgetcsv();
        $index = 1;

        while (!$csv->eof()) {
            $row = $csv->fgetcsv();
            $index++;

            if ($this->isEmptyRow($row)) {
                continue;
            }

            try {
                $rowData = $this->mapRowToData($row);

                $this->validate($rowData, ['isbn' => ['required', 'string', 'max:50']]);

                $book = Book::where('isbn', $rowData['isbn'])->first();

                if ($book) {
                    $this->validate($rowData, (new UpdateBookRequest())->rules());
                    $this->bookService->update($book, $rowData);
                } else {
                    $this->validate($rowData, (new StoreBookRequest())->rules());
                    $this->bookService->create($rowData);
                }

                $result['imported']++;
            } catch (\Throwable $e) {
                $result['failed']++;
                $result['errors'][] = [
                    'row' => $index,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $result;
    }

    private function isEmptyRow(array $row): bool
    {
        return $row === [null];
    }

    private function mapRowToData(array $row): array
    {
        return [
            'author_ids' => $this->resolveAuthorIds(explode(';', $row[0])),
            'title' => trim($row[1]),
            'genre_ids' => $this->resolveGenreIds(explode(';', $row[2])),
            'description' => trim($row[3]),
            'edition' => $row[4] ?? null,
            'publisher_id' => $this->resolvePublisherId(trim($row[5])),
            'published_at' => trim($row[6]),
            'format' => trim($row[7]),
            'pages' => $row[8],
            'country_code' => $this->resolveCountryCode(trim($row[9])),
            'isbn' => trim($row[10]),
        ];
    }

    private function validate(array $rowData, array $rules): void
    {
        $validator = Validator::make($rowData, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private function resolveAuthorIds(array $authors): array
    {
        return collect($authors)
            ->map(fn ($author) =>
            Author::firstOrCreate(['author' => $author])->id
            )
            ->toArray();
    }

    private function resolveGenreIds(array $genres): array
    {
        return collect($genres)
            ->map(fn ($genre) =>
            Genre::firstOrCreate(['genre' => $genre])->id
            )
            ->toArray();
    }

    private function resolvePublisherId(string $publisher): int
    {
        $publisher = Publisher::firstOrCreate(['publisher' => $publisher]);

        return $publisher->id;
    }

    private function resolveCountryCode(string $countryName): string
    {
        $country = Country::where('name', $countryName)->first();

        if (!$country) {
            throw new \DomainException(
                "Unknown country: {$countryName}"
            );
        }

        return $country->code;
    }
}
