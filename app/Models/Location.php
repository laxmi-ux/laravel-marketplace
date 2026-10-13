<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Location extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'state',
        'description',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function listings()
    {
        return $this->hasMany(Listing::class, 'city', 'name');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}