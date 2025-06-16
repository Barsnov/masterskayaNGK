<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'categories';
    protected $fillable = [
        'name'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }
}
