<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $table = 'images';

    protected $fillable = [
        'url', 'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
