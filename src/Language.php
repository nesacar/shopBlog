<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public static $list_limit = 50;

    protected $table = 'languages';

    protected $fillable = ['name', 'fullname', 'locale', 'publish'];

    public function setting(){
        return $this->hasMany(Setting::class);
    }

    public function product(){
        return $this->hasMany(Product::class);
    }

    public function post(){
        return $this->hasMany(Post::class);
    }

    public function subscriber(){
        return $this->hasMany('App\Subscriber');
    }

    public function newsletter(){
        return $this->hasMany('App\Newsletter');
    }
}
