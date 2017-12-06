<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Images extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'file_name', 'file_size', 'file_mime', 'file_path', 'file_path_small', 'color', 'material'];

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public static function getNum($str){
        $tacka = stripos($str, ".");
        $crta = strripos($str, "-");
        $razlika = $tacka - $crta;
        return $ono = substr($str, $crta+1, $razlika-1);
    }

    public static function thumbnail($img){
        $img = \Image::make($img)->resize(300, 200);
        return $img->response('jpg');
    }

}
