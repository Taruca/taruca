<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    protected $guarded = [];

    public function tags() {
        return $this->belongsToMany('App\Models\Tag')->withTimestamps();
    }
}
