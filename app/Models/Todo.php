<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory; // 🔥 Pastikan ini ada

    protected $fillable = [
        'title', 
        'description', 
        'is_completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
