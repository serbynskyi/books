<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['country'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
