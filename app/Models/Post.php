<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'title_en',
        'title_nl',
        'title_fr',
        'title_es',
        'content_en',
        'content_nl',
        'content_fr',
        'content_es',
        'slug_en',
        'slug_nl',
        'slug_fr',
        'slug_es',
        'is_published',
    ];


    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

}
