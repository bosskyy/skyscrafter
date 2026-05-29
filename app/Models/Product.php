<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'price', 'image'];

    // Ini adalah "Tali Penyambung" dari Product ke Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}