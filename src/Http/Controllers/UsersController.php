<?php

namespace App\Http\Controllers;

use App\Customer;
use App\PCategory;
use App\Http\Requests\CreateAdminUserRequest;
use App\Http\Requests\EditAdminUserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use File;
use Jenssegers\Date\Date;

class UsersController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        app()->setLocale('sr');
        $slug = 'users';
        $text = \Session::get('user_text');
        $role = \Session::get('user_role');
        if(\Auth::user()->role == 5) {
            $users = User::where(function ($query) use ($text) {
                if (isset($text) && $text != '') {
                    return $query->where('email', 'like', '%' . $text . '%');
                }
            })
                ->where(function ($query) use ($role) {
                    if (isset($role) && $role != -1) {
                        return $query->where('role', $role);
                    }
                })->orderby('id', 'DESC')->paginate(User::$list_limit);
            $roles = [-1 => 'Svi korisnici', 0 => 'Kupac', 1 => 'Urednik', 2 => 'Glavni urednik', 3 => 'Menager', 4 => 'Admin', 5 => 'Developer'];
        }else{
            $users = User::where(function ($query) use ($text) {
                if (isset($text) && $text != '') {
                    return $query->where('email', 'like', '%' . $text . '%');
                }
            })->where(function ($query) use ($role) {
                if (isset($role) && $role != -1) {
                    return $query->where('role', $role)->where('role', '<>', 5);
                }
            })->where('role', '<', 5)->orderby('id', 'DESC')->paginate(User::$list_limit);
            $roles = [-1 => 'Svi korisnici', 0 => 'Kupac', 1 => 'Urednik', 2 => 'Glavni urednik', 3 => 'Menager', 4 => 'Admin'];
        }

        return view('admin.users.index', compact('slug', 'users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $slug = 'users';
        return view('admin.users.create', compact('slug'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAdminUserRequest $request)
    {
        $user = User::create($request->all());
        if($request->input('password')){
            $user->password = bcrypt($request->input('password'));
        }
        $user->update();

        if($request->input('role')  == 0){
            $user->customer()->save(new Customer($request->only('name', 'lastname', 'phone', 'email', 'company', 'address', 'town', 'state', 'postcode', 'block')));
        }

        return redirect('admin/users')->with('done', 'Korinsik je kreiran');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $slug = 'users';
        return view('admin.users.edit', compact('user', 'slug'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(EditAdminUserRequest $request, User $user)
    {
        if(User::availableEmail($request->input('email'), $user->id)){
            if($request->input('password')){
                $user->password = bcrypt($request->input('password'));
            }
            $user->username = $request->input('username');
            $request->input('block')? $user->block = 1 : $user->block = 0;
            $user->role = $request->input('role');
            $user->update();

            if($request->input('role') == 0){
                $customer = Customer::where('user_id', $user->id)->first();
                if(!empty($customer)){
                    $user->customer()->update($request->only('name', 'lastname', 'phone', 'email', 'company', 'address', 'town', 'state', 'postcode', 'block'));
                }else{
                    $user->customer()->save(new Customer($request->only('name', 'lastname', 'phone', 'email', 'company', 'address', 'town', 'state', 'postcode', 'block')));
                }
            }else{
                $customer = Customer::where('user_id', $user->id)->first();
                if(!empty($customer)){
                    $customer->delete();
                }
            }

            return redirect('admin/users/'.$user->id.'/edit')->with('done', 'Korisnik je izmenjen.');
        }else{
            return redirect()->back()->with('error', 'Email adresa je već zauzeta.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function delete($id)
    {
        $user = User::find($id);
        if(isset($user)) $user->delete();
        return redirect('admin/users')->with('done', 'Korisnik je obrisan');
    }

    public function search(Request $request){
        \Session::put('user_text', $request->input('text'));
        \Session::put('user_role', $request->input('role'));
        return redirect('admin/users');
    }
}
