<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cart extends Model {

    public static $minutes = 2;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'carts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id', 'payment_id', 'sum', 'status'];

    public static function cartSum($id){
        $cart = Cart::find($id);
        $sum = 0;
        foreach($cart->product as $p){
            $sum += $p->pivot->price;
        }
        return $sum;
    }

    public static function status($id){
        $str='';
        if($id == 0){
            $str = 'na &#269;ekanju';
        }elseif($id == 1){
            $str = 'potv&#273;eno';
        }elseif($id == 2){
            $str = 'odbijeno';
        }elseif($id == 3){
            $str = 'otkazano';
        }
        return $str;
    }

    public static function boja($id){
        $str='';
        if($id == 0){
            $str = 'plava';
        }elseif($id == 1){
            $str = 'zelena';
        }elseif($id == 2){
            $str = 'crvenoj';
        }elseif($id == 3){
            $str = 'narandzasta';
        }
        return $str;
    }

    public static function bojaHDEX($id){
        $str='';
        if($id == 0){
            $str = '#00688B';
        }elseif($id == 1){
            $str = '#84d084';
        }elseif($id == 2){
            $str = '#ca0002';
        }elseif($id == 3){
            $str = '#ffb347';
        }
        return $str;
    }

    public static function getProductCount($cart_id, $product_id){
        $pr = DB::table('cart_product')->where('cart_id', $cart_id)->where('product_id', $product_id)->get();
        return count($pr);
    }

    public static function getProductPrice($cart_id, $product_id){
        $cart = self::find($cart_id);
        return $cart->product()->where('id', $product_id)->first()->pivot->price * self::getProductCount($cart_id, $product_id);
    }

    public static function filteredCarts($s, $od, $do, $datod, $datdo){
        return self::select('carts.*', 'users.email as email', 'customers.name as name', 'customers.lastname as lastname')
            ->join('customers', 'carts.customer_id', '=', 'customers.id')
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->join('cart_product', 'carts.id', '=', 'cart_product.id')
            ->where(function($query) use ($s){
                if($s){
                    $query->where('users.email', 'LIKE', "%$s%");
                }
            })->where(function($query) use ($od, $do){
                if($od){
                    $query->where('carts.sum', '>=', $od);
                }
                if($do){
                    $query->where('carts.sum', '<=', $do);
                }
            })->where(function($query) use ($datod, $datdo){
                if($datod){
                    $query->where('carts.created_at', '>=', $datod);
                }
                if($datdo){
                    $query->where('carts.created_at', '<=', $datdo);
                }
            })->groupBy('carts.id')->orderBy('carts.created_at', 'DESC')->paginate(50);
    }

    public static function addToSession($id, $q, $s, $c, $m){
        $korpa = array(); $qty = array(); $size = array(); $color = array(); $material = array();
        if(\Session::has('korpa')){ //id
            $korpa = \Session::pull('korpa');
        }
        if(\Session::has('qty')){ //id
            $qty = \Session::pull('qty');
        }
        if(\Session::has('size')){ //id
            $size = \Session::pull('size');
        }
        if(\Session::has('color')){ //id
            $color = \Session::pull('color');
        }
        if(\Session::has('material')){ //id
            $material = \Session::pull('material');
        }
        if(isset($id)){
            $korpa[] = $id;
            $qty[] = $q;
            $size[] = $s;
            $color[] = $c;
            $material[] = $m;
        }
        $korpa = array_unique($korpa);
        \Session::put('korpa', $korpa); \Session::put('qty', $qty); \Session::put('size', $size); \Session::put('color', $color); \Session::put('material', $material);
    }

    public static function addToWishlist($id, $q, $s, $c){
        $minutes = self::$minutes;
        $korpa = array(); $qty = array(); $size = array(); $color = array();
        $cookie = \App::make('CodeZero\Cookie\Cookie');
        if(count($cookie->get('korpa')) > 0){ //id
            $korpa = $cookie->get('korpa');
        }
        if(count($cookie->get('qty')) > 0){ //id
            $qty = $cookie->get('qty');
        }
        if(count($cookie->get('size')) > 0){ //id
            $size = $cookie->get('size');
        }
        if(count($cookie->get('color')) > 0){ //id
            $color = $cookie->get('color');
        }
        if(isset($id)){
            $korpa[] = $id;
            $qty[] = $q;
            $size[] = $s;
            $color[] = $c;
        }
        $korpa = array_unique($korpa);
        $cookie->store('korpa', $korpa, $minutes); $cookie->store('qty', $qty, $minutes); $cookie->store('size', $size, $minutes); $cookie->store('color', $color, $minutes);
    }

    public static function removeToSession($req){
        $new = array(); $new2 = array(); $new3 = array(); $new4 = array(); $new5 = array();
        if(\Session::has('korpa') && is_array(\Session::get('korpa'))) {
            $korpa = \Session::get('korpa'); $qty = \Session::get('qty'); $size = \Session::get('size'); $color = \Session::get('color'); $material = \Session::get('material');
            for($i=0;$i<count($korpa);$i++){
                if(!in_array($korpa[$i], $req)){
                    $new[] = $korpa[$i];
                    $new2[] = $qty[$i];
                    $new3[] = $size[$i];
                    $new4[] = $color[$i];
                    $new5[] = $material[$i];
                }
            }
            \Session::set('korpa', $new); \Session::set('qty', $new2); \Session::set('size', $new3); \Session::set('color', $new4); \Session::set('material', $new5);
        }
    }

    public static function removeToWishlist($req){
        $new = array(); $new2 = array(); $new3 = array(); $new4 = array();
        $minutes = self::$minutes;
        $cookie = \App::make('CodeZero\Cookie\Cookie');
        if(count($cookie->get('korpa')) > 0) {
            $korpa = $cookie->get('korpa'); $qty = $cookie->get('qty'); $size = $cookie->get('size'); $color = $cookie->get('color');
            for($i=0;$i<count($korpa);$i++){
                if(!in_array($req, $korpa)){
                    $new[] = $korpa[$i];
                    $new2[] = $qty[$i];
                    $new3[] = $size[$i];
                    $new4[] = $color[$i];
                }
            }
            $cookie->store('korpa', $new, $minutes); $cookie->store('qty', $new2, $minutes); $cookie->store('size', $new3, $minutes); $cookie->store('color', $new4, $minutes);
        }
    }

    public static function addToFilterSession($req){
        $filter = array();
        if(\Session::has('filter')){
            $filter = \Session::pull('$filter');
        }
        if(isset($req)){
            foreach($req as $r){
                $filter[] = $r;
            }
        }
        $filter = array_unique($filter);
        \Session::put('filter', $filter);
    }

    public static function removeToFilterSession($req){
        $new = array();
        if(\Session::has('filter') && is_array(\Session::get('filter'))) {
            $filter = \Session::get('filter');
            foreach($filter as $f) {
                if(!in_array($f, $req)){
                    $new[] = $f;
                }
            }
            \Session::set('filter', $new);
        }
    }

    public static function storeCart($user, $suma, $id, $kol){
        $cart = new Cart();
        $cart->user_id = $user;
        $cart->new = 1;
        $cart->country_id = 1;
        $cart->sum = $suma;
        $cart->status = 1;
        $cart->payment = 1;
        $cart->end = 1;
        $cart->save();

        for($i=0;$i<count($id);$i++){
            for($j=0;$j<$kol[$i];$j++){
                $cart->product()->attach($id[$i]);
            }
        }
        return $cart;
    }

    public static function checkFilterNav($niz, $cat=false, $id=false){
        $res=false;
        if(\Session::has('filter')){
            if($id && $cat){
                if(in_array($id, \Session::get('filter'))){
                    $res=true;
                }
            }else{
                if(count($niz)){
                    foreach($niz as $n){
                        if(in_array($n->id, \Session::get('filter'))){
                            $res=true;
                        }
                    }
                }
            }
        }
        return $res;
    }

    public static function sendEmailCart($cart_id){
        $cart = self::find($cart_id);
        $user = User::find($cart->user_id);
        $settings = Settings::find(1);

        $to      = $user->email;
        $subject = 'Obaveštenje o porudžbini | pggrupa.rs';
        $message = self::makeEmailCart($cart->id, 1); // zahvalnica
        $headers = 'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=UTF-8' . "\r\n" .
            'From: office@pggrupa.rs' . "\r\n" .
            'Reply-To: office@pggrupa.rs' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);

        $to      = $settings->email1;
        $subject = 'Zahtev za porudžbinu | pggrupa.rs';
        $message = self::makeEmailCart($cart->id); // Zahtev za porudžbinu
        $headers = 'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=UTF-8' . "\r\n" .
            'From: office@pggrupa.rs' . "\r\n" .
            'Reply-To: office@pggrupa.rs' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }

    public static function sendDummyMail($cart_id){
        $cart = self::find($cart_id);
        $user = User::find($cart->user_id);

        $to      = $user->email;
        $subject = 'Nova porudžbina na sajtu pggrupa.rs';
        $message = 'Uspešno ste izvrvšili porudžbinu na sajtu PGGrupa.rs. Očekujte uskoro poziv operatera.';
        $headers = 'From: info@pggrupa.rs' . "\r\n" .
            'Reply-To: info@pggrupa.rs' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }

    public static function checkFilters($niz, $id=false){
        $res=false;
        if($id){
            if(in_array($id, $niz)){
                $res=true;
            }
        }else{
            if(count($niz)){
                foreach($niz as $n){
                    if(in_array($n->id, $niz)){
                        $res=true;
                    }
                }
            }
        }
        return $res;
    }

    public static function getMaterialTitle($cart_id, $product_id){
        $material = DB::table('cart_product')->where('cart_id', $cart_id)->where('product_id', $product_id)->first();
        if(isset($material) && $material->material != 0){
            $att = Attribute::find($material->material);
            return $att->{'title:sr'};
        }else{
            return '';
        }
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function product(){
        return $this->belongsToMany('App\Product')->withPivot('color', 'size', 'price');
    }

}
