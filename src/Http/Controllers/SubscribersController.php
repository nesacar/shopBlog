<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscribersController extends Controller {

	public function __construct(){
		$this->middleware('menager', ['except' => ['subscribe']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$slug = 'newsletters';
		$subscribers = Subscriber::paginate(50);
		return view('admin.subscribers.index', compact('subscribers', 'slug'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$slug = 'newsletters';
		return view('admin.subscribers.create', compact('slug'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreateSubscribersRequest $request)
	{
		$sub = Subscriber::create($request->all());
		$request->input('block')? $sub->block = 1 : $sub->block = 0;
		$sub->verification = str_random(32);
		$sub->update();
		return redirect('admin/subscribers')->with('done', 'Pretplatnik je kreiran.');
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
		$slug = 'newsletters';
		$sub = Subscriber::find($id);
		return view('admin.subscribers.edit', compact('slug', 'sub'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Requests\EditSubscribersRequest $request, $id)
	{
		$sub = Subscriber::find($id);
		if(Subscriber::isAvailableEmail($request->input('email'), $id)){
			$sub->email = $request->input('email');
			$request->input('block')? $sub->block = 1 : $sub->block = 0;
			$sub->update();
			return redirect('admin/subscribers/'.$sub->id.'/edit')->with('done', 'Pretplatnik je izmenjen');
		}else{
			return redirect()->back()->with('error', 'Već postoji osoba koja je prijavljena sa ovim emailom.');
		}
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
		$sub = Subscriber::find($id);
		$sub->delete();
		return redirect('admin/subscribers');
	}

	public function publish($id){
		$val = Input::get('val');
		if($val == 'true'){ $primary = 1; }else{ $primary = 0; }
		$sub = Subscriber::find($id)->update(array('block' => $primary));
		if(isset($sub)){ return 'da'; }else{ return 'ne'; }
	}

	public function subscribe(Requests\CreateSubscribersRequest $request)
	{
		$sub = Subscriber::create($request->all());
		$sub->verification = str_random(32);
		$sub->update();
		return redirect()->back()->with('done', 'Korisnik je pretplaćen.');
	}

	public function search(Request $request){
		$slug = 'newsletters';
		$title = $request->input('email');
		\Session::set('sub_title', $title);
		$subscribers = Subscriber::where('email', 'like', '%'.$title.'%')->paginate(50);
		return view('admin.subscribers.index', compact('subscribers', 'slug'));
	}

	public function removesearch(){
		\Session::forget('sub_title');
		return redirect('admin/subscribers');
	}

}
