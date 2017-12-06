<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public static $list_limit = 50;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password', 'role', 'block'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function isCustomer(){
        if(\Auth::check() && \Auth::user()->role == 0){ return true; }else{ return false; }
    }

    public function isWriter(){
        if(\Auth::check() && \Auth::user()->role == 1){ return true; }else{ return false; }
    }

    public function isMainWriter(){
        if(\Auth::check() && \Auth::user()->role > 1){ return true; }else{ return false; }
    }

    public function isMenager(){
        if(\Auth::check() && \Auth::user()->role > 2){ return true; }else{ return false; }
    }

    public function isAdmin(){
        if(\Auth::check() && \Auth::user()->role > 3){ return true; }else{ return false; }
    }

    public function isDeveloper(){
        if(\Auth::check() && \Auth::user()->role > 4){ return true; }else{ return false; }
    }

    public static function oldPassword($password, $id){
        $user = User::find($id);
        if(\Hash::check($password, $user->password)) { return true; }else{ return false; }
    }

    public static function availableUsername($username, $id){
        $user = User::find($id);
        $u = User::where('username', $username)->first();
        if(isset($u) && $username == $u->username && $u->id == $user->id){
            return true;
        }elseif(!isset($u)){
            return true;
        }else{
            return false;
        }
    }

    public static function availableEmail($email, $id){
        $user = User::find($id);
        $u = User::where('email', $email)->first();
        if(isset($u) && $email == $u->email && $u->id == $user->id){
            return true;
        }elseif(!isset($u)){
            return true;
        }else{
            return false;
        }
    }

    public static function currentCustomer($id){
        if(\Auth::user()->isAdmin() || $id == \Auth::user()->id){ return true; }else{ return false; }
    }

    public static function lastVisit(){
        User::find(\Auth::user()->id)->update(array('last_visit' => new \Carbon\Carbon()));
    }

    public static function getRole($user_id){
        $user = User::find($user_id);
        if($user->role == 0){
            return 'kupac';
        }elseif($user->role == 1){
            return 'novinar';
        }elseif($user->role == 2){
            return 'glavni urednik';
        }elseif($user->role == 3){
            return 'menager';
        }elseif($user->role == 4){
            return 'admin';
        }else{
            return 'developer';
        }
    }

    public function post(){
        return $this->hasMany(Post::class);
    }

    public function product(){
        return $this->hasMany(Product::class);
    }

    public function customer(){
        return $this->hasOne(Customer::class);
    }
}
