<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    public static $list_limit = 50;

    protected $table = 'blocks';

    protected $fillable = ['title', 'desc', 'publish'];

    public function box(){
        return $this->hasMany(Box::class);
    }
}
