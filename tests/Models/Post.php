<?php

namespace AhmetBarut\FullSearch\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \AhmetBarut\FullSearch\Tests\Database\Factories\PostFactory::new();
    }
}
