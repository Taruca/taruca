<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    public function segments() {
        return $this->belongsToMany('App\Models\Segment')->withTimestamps();
    }
}
