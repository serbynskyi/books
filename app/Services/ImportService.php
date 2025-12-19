<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Http\UploadedFile;
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
                $data = $this->mapRowToData($row);

                $book = Book::where('isbn', $data['isbn'])->first();

                $book ? $this->bookService->update($book, $data) : $this->bookService->create($data);

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

    private function mapRowToData(array $row): array
    {
        return [
            'authors' => explode(';', $row[0]),
            'title' => trim($row[1]),
            'genres' => explode(';', $row[2]),
            'description' => trim($row[3]),
            'edition' => $row[4] ?? null,
            'publisher' => trim($row[5]),
            'published_at' => trim($row[6]),
            'format' => trim($row[7]),
            'pages' => $row[8],
            'country' => trim($row[9]),
            'isbn' => trim($row[10]),
        ];
    }

    private function isEmptyRow(array $row): bool
    {
        return $row === [null];
    }
}
