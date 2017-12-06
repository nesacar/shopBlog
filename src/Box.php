<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;

class Box extends Model
{
    use Translatable;

    public static $list_limit = 50;

    public $translatedAttributes = ['title', 'subtitle', 'button', 'link'];

    protected $table = 'boxes';

    protected $fillable = ['block_id', 'image', 'order', 'publish'];

    public static function getHttpLink($link){
        if(substr($link, 0, 4) == 'http'){
            return $link;
        }else{
            return url($link);
        }
    }

    public function block(){
        return $this->belongsTo(Block::class);
    }
}
