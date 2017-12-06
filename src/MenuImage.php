<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use Session;

class MenuImage extends Model
{
    use Translatable;

    public static $list_limit = 50;

    public $translatedAttributes = ['title', 'button', 'link'];

    protected $table = 'menu_images';

    protected $fillable = ['id', 'menu_id', 'image', 'order', 'publish'];

    public function menu(){
        return $this->belongsTo(Menu::class);
    }
}
