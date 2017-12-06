<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PCategoryTranslation extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'p_category_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'desc', 'body'];
}
