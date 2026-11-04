<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'slug'];

    // Otomatis membuat slug dari nama, misalnya "Teknologi Baru" -> "teknologi-baru"
    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            $category->slug = Str::slug($category->nama);
        });
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
