<?php
// app/Models/Menu.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // Secara default Laravel sudah menggunakan tabel 'menus'
    // Tapi kita bisa eksplisit mendefinisikan jika perlu
    protected $table = 'menus';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];
}