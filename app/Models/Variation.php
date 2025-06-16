<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    //
    protected $table = 'variations';
    protected $fillable = [];

    public function category()
    {
        return $this->hasMany(category::class);
    }
}
