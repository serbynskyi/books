<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'description',
        'edition',
        'publisher_id',
        'published_at',
        'format',
        'pages',
        'country_code',
        'isbn',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function country()
    {
        return $this->belongsTo(
            Country::class,
            'country_code',
            'code'
        );
    }

    public function scopeSort(Builder $query, ?string $column, ?string $direction): Builder
    {
        $sortableColumns = [
            'id',
            'title',
            'description',
            'edition',
            'published_at',
            'format',
            'pages',
            'country_code',
            'isbn',
            'created_at',
        ];

        if (!in_array($column, $sortableColumns, true)) {
            $column = 'id';
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        return $query->orderBy($column, $direction);
    }
}
