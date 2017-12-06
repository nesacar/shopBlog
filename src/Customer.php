<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'name', 'lastname', 'phone', 'email', 'company', 'address', 'town', 'state', 'country', 'postcode', 'block'];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
