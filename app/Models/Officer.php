<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    protected $table = 'market_officers';

    protected $fillable = ['name', 'nik', 'phone', 'image_url', 'market_id', 'is_active','username', 'password'];
    
    protected $hidden = [
        'password',
    ];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
