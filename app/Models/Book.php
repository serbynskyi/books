<?php

namespace App\Models;

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
        'country_id',
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
        return $this->belongsTo(Country::class);
    }
}
