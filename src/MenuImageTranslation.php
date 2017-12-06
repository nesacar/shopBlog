<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuImageTranslation extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu_image_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'button', 'link'];
}
