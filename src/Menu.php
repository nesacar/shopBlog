<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public static $list_limit = 50;

    protected $table = 'menus';

    protected $fillable = ['title', 'slug', 'prefix', 'sufix', 'class', 'publish'];

    public function menuLinks(){
        return $this->hasMany(MenuLink::class);
    }

    public function menuImages(){
        return $this->hasMany(MenuImage::class);
    }
}
