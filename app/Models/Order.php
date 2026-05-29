<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan input data
    protected $fillable = [
        'product_id',
        'customer_name',
        'customer_whatsapp',
        'quantity',
        'note',
        'status',
        'payment_proof'
    ];

    // Relasi ke produk (agar kita tahu produk apa yang dipesan)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}