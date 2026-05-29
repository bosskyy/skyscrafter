<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    // Ini adalah "Tali Penyambung" dari Category ke banyak Product
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}