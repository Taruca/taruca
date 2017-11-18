<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    public function scopeActive($query) {
        return $query->where('hide', 0);
    }

    public function getAllMenus() {
        $menus = $this->select('id', 'parent_id', 'icon', 'name', 'route', 'sort')
            ->orderBy('parent_id', 'sort')->get()->groupBy('parent_id');
        return $menus;
    }

    public function getActiveMenus() {
        $menus = $this->select('id', 'parent_id', 'icon', 'name', 'route', 'sort')
            ->active()->orderBy('parent_id', 'sort')->get()->groupBy('parent_id');
        return $menus;
    }
}
