<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan input data
    protected $fillable = [
        'checkout_id',
        'product_id',
        'customer_name',
        'customer_whatsapp',
        'quantity',
        'note',
        'additional_note',
        'delivery_method',
        'delivery_address',
        'document_file',
        'options',
        'status',
        'payment_proof',
        'payment_validated'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    // Relasi ke produk (agar kita tahu produk apa yang dipesan)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function totalPrice(): int
    {
        $unitPrice = (int) ($this->product->price ?? 0);
        
        $variant = data_get($this->options, 'variant');
        if ($this->product->name === 'Polaroid & Photostrip') {
            if ($variant === 'Polaroid') {
                $unitPrice = 20000;
            } elseif ($variant === 'Photostrip') {
                $unitPrice = 5000;
            }
        }

        $quantity = (int) ($this->quantity ?? 0);

        $copies = (int) data_get($this->options, 'copies', 1);
        if ($copies < 1) {
            $copies = 1;
        }

        return $unitPrice * $quantity * $copies;
    }

    public function optionsSummary(): string
    {
        $options = $this->options ?? [];

        $parts = [];
        $variant = data_get($options, 'variant');
        if ($variant === 'warna') {
            $parts[] = 'Warna';
        } elseif ($variant === 'hitam_putih') {
            $parts[] = 'Hitam Putih';
        } elseif (in_array($variant, ['Polaroid', 'Photostrip'])) {
            $parts[] = 'Tipe: ' . $variant;
        }

        $template = data_get($options, 'photostrip_template');
        if ($template) {
            $parts[] = 'Template: ' . str_replace('.png', '', $template);
        }

        $size = data_get($options, 'size');
        if ($size) {
            $parts[] = 'Ukuran ' . $size;
        }

        $copies = data_get($options, 'copies');
        if ($copies) {
            $parts[] = 'Rangkap ' . $copies;
        }

        $bindingColor = data_get($options, 'binding_color');
        if ($bindingColor) {
            $parts[] = 'Warna Jilid ' . $bindingColor;
        }

        return implode(', ', $parts) ?: '-';
    }
}