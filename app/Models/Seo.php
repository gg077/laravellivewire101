<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seo extends Model
{
    /** @use HasFactory<\Database\Factories\SeoFactory> */
    use HasFactory;

    // The model has implicit fields: en, nl, fr for each language
}
