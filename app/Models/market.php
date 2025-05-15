<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    protected $table = 'markets'; // Opsional jika tabel berbeda dari konvensi Laravel

    protected $fillable = [
        'name',
        'location',
        'latitude',
        'longitude',
        'image_url',
    ];

    // Jika ingin mengakses gambar dengan URL langsung
    public function getImageUrlAttribute()
    {
        return $this->image_url ? asset('storage/' . $this->image_url) : null;
    }
}
