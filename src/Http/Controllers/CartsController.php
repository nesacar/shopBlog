<?php namespace App\Http\Controllers;

use App\Cart;
use App\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\PDF;
use Session;
use DB;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CartsController extends Controller {

	public function __construct() {
		$this->middleware('menager');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$slug = 'carts';
		$carts = Cart::filteredCarts(Session::get('cart_title'), Session::get('cart_od'), Session::get('cart_do'), Session::get('cart_datod'), Session::get('cart_datdo'), Session::get('cart_end'));
		$ends = array('0' => 'Aktivne kupovine', '1' => 'Zavrsene kupovine', '2' => 'Sve kupovine');
		return view('admin.carts.index', compact('carts', 'slug', 'ends'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$slug = 'carts';
		$customers = User::orderby('name', 'ASC')->pluck('name', 'id');
		$stat = array(0 => 'na &#269;ekanju', 1 => 'potvr&#273;eno', 2 => 'odbijeno', 3 => 'otkazano');
		$payments = array('0' => 'pouze&#263;em');
		$ends = array('0' => 'jeste', '1' => 'nije');
		return view('admin.carts.create', compact('customers', 'stat', 'payments', 'slug', 'ends'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$cart = Cart::storeCart($request->input('user_id'), $request->input('suma'), $request->input('id'), $request->input('kol'));
		return redirect('admin/carts')->with('done', 'Porudžbina je kreirana.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$slug = 'carts';
		$cart = Cart::find($id);
		$stat = array(0 => 'na &#269;ekanju', 1 => 'potvr&#273;eno', 2 => 'odbijeno', 3 => 'otkazano');
		$payments = array('0' => 'pouze&#263;em');
		$ends = array('0' => 'jeste', '1' => 'nije');
		$customers = User::orderby('name', 'ASC')->pluck('name', 'id');
		$products = $cart->product()->groupby('product_id', 'cart_id')->get();
		return view('admin.carts.edit', compact('cart', 'stat', 'payments', 'customers', 'slug', 'products', 'ends'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$cart = Cart::find($id);
		$cart->sum = Cart::cartSum($cart->id);
		$cart->update($request->except('sum'));
		return redirect()->back()->with('done', 'Porudžbina je izmenjena.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function delete($id)
	{
		Cart::find($id)->delete();
		return redirect('admin/carts')->with('done', 'Porudžbina je obrisana.');
	}

	public function search(Request $request){
		$title = $request->input('title'); Session::set('cart_title', $title);
		$od = $request->input('od'); Session::set('cart_od', $od);
		$do = $request->input('do'); Session::set('cart_do', $do);
		$datod = $request->input('datod'); Session::set('cart_datod', $datod);
		$datdo = $request->input('datdo'); Session::set('cart_datdo', $datdo);
		$end = $request->input('end'); Session::set('cart_end', $end);
		$carts = Cart::filteredCarts(Session::get('cart_title'), Session::get('cart_od'), Session::get('cart_do'), Session::get('cart_datod'), Session::get('cart_datdo'), Session::get('cart_end'));
		return view('admin.carts.index_append', compact('carts'));
	}

	public function add($id)
	{
		$slug = 'carts';
		$products = Product::filteredProducts(Session::get('title'), Session::get('cat'), Session::get('od'), Session::get('do'));
		$catids = Category::where('publish', 1)->pluck('title', 'id');
		return view('admin.carts.add', compact('products', 'slug', 'catids', 'id'));
	}

	public function removeAll(Request $request){
		$ids = $request->input('all');
		if(isset($ids)){
			foreach($ids as $i){
				Cart::find($i)->delete();
			}
		}
		return redirect()->back()->with('done', 'Porudžbina je obrisana.');
	}

	public function addProduct(Request $request){
		$cart_id = $request->input('cart_id');
		$product_id = $request->input('product_id');
		$num = $request->input('num');
		if($num > 0){
			if($num == 0){
				Cart::find($cart_id)->product()->detach([$product_id]);
			}else{
				Cart::find($cart_id)->product()->detach([$product_id]);
				for($i=0;$i<$num;$i++){
					Cart::find($cart_id)->product()->attach([$product_id]);
				}
			}
		}
		return redirect('admin/carts/'.$cart_id.'/edit')->with('done', 'Porudzbina je izmenjena.');
	}

	public function removeProduct(Request $request){
		$cart_id = $request->input('cart_id');
		$product_id = $request->input('product_id');
		Cart::find($cart_id)->product()->detach([$product_id]);
		return redirect('admin/carts/'.$cart_id.'/edit')->with('done', 'Proizvod je obrisan iz korpe.');
	}

	public function korpa(){
		return view('admin.carts.step1');
	}

	public function clear()
	{
		Session::forget('cart_title');
		Session::forget('cart_od');
		Session::forget('cart_do');
		Session::forget('cart_datod');
		Session::forget('cart_datdo');
		Session::forget('cart_end');
		return redirect()->back();
	}

	public function clearfilter(){
		Session::forget('filter');
		return redirect('admin/products/cart');
	}

	public function step2(){
		if(!\Auth::check()){ return redirect('register/login'); }
		$id = Input::get('id');
		$kol = Input::get('cen');
		$sum=0;
		for($i=0;$i<count($id);$i++){
			$p = Product::find($id[$i]);
			$sum += $p->price_small*$kol[$i];
		}
		$user = User::find(\Auth::user()->id);
		return view('admin.carts.step2', compact('user', 'id', 'kol', 'sum'));
	}

	public function pdf($id){
		$cart = Cart::find($id);
		$products = $cart->product()->groupby('product_id')->get();
		$pdf = \PDF::loadView('admin.carts.pdf', compact('products', 'cart'));
		return $pdf->stream();
	}

	public function finish(Request $request, $id){
		$status = $request->input('status');
		$cart = Cart::find($id);
		if(isset($cart)){
			$cart->status = $status;
			$cart->update();
			return redirect()->back()->with('done', 'Status porudžbine je promenjen.');
		}else{
			return redirect()->back()->with('error', 'Porudžbine nije pronadjena.');
		}
	}
}
