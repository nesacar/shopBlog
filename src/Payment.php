<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public static $list_limit = 50;

    protected $table = 'payments';

    protected $fillable = ['title', 'order', 'publish'];

    public function cart(){
        return $this->belongsTo(Cart::class);
    }
}
