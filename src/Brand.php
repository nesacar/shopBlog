<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;

class Brand extends Model
{
    use Translatable;

    public static $list_limit = 50;

    protected $table = 'brands';

    public $translatedAttributes = ['title', 'slug', 'short', 'body', 'body2'];

    protected $fillable = ['id', 'order', 'logo', 'image', 'publish'];

    public function category(){
        return $this->hasMany(Category::class);
    }

    public function attribute(){
        return $this->belongsToMany(Attribute::class);
    }
}
