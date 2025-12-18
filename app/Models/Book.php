<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'authors',
        'title',
        'genres',
        'description',
        'edition',
        'publisher_id',
        'published_at',
        'format',
        'pages',
        'country_id',
        'isbn',
    ];

    protected $casts = [
        'authors' => 'array',
        'genres' => 'array',
        'published_at' => 'date',
    ];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
