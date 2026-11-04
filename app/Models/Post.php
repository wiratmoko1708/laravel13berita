<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'judul',
        'slug',
        'ringkasan',
        'isi',
        'gambar',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Scope: hanya berita yang sudah terbit dan waktunya sudah tiba
    public function scopeTerbit($query)
    {
        return $query->where('status', 'terbit')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
