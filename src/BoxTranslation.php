<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoxTranslation extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'box_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'subtitle', 'button', 'link'];
}
