<?php namespace App\Http\Controllers;

use App\Attribute;
use App\Block;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Language;
use App\Osobina;
use App\Product;
use App\Property;
use App\Theme;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AttributesController extends Controller {

	public function __construct(){
		$this->middleware('menager');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		\Session::forget('attribute_title');
		$slug = 'products';
		$attributes = Attribute::orderBy('property_id', 'ASC')->paginate(50);
		return view('admin.attributes.index', compact('attributes','slug'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$slug = 'products';
		$properties = Property::join('property_translations', 'properties.id', '=', 'property_translations.property_id')
            ->where('properties.publish', 1)->orderby('property_translations.title', 'ASC')
            ->pluck('property_translations.title', 'properties.id');
		return view('admin.attributes.create', compact('slug', 'properties'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreateAttributeRequest $request)
	{
		app()->setLocale('sr');
		$attribut = Attribute::create($request->all());
        $attribut->title = $request->input('title');
		$request->input('publish')? $attribut->publish = 1 : $attribut->publish = 0;
		$attribut->update();
		return redirect('admin/attributes')->with('done', 'Atribut je kreiran.');
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
		$attribute = Attribute::find($id);
		$slug = 'products';
        $properties = Property::join('property_translations', 'properties.id', '=', 'property_translations.property_id')
            ->where('properties.publish', 1)->orderby('property_translations.title', 'ASC')
            ->pluck('property_translations.title', 'properties.id');
        $languages = Language::where('publish', 1)->orderBy('order', 'ASC')->get();
		return view('admin.attributes.edit', compact('slug', 'attribute', 'properties', 'languages'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		app()->setLocale('sr');
		$attribute = Attribute::find($id);
		if($attribute){
			$request->input('publish')? $attribute->publish = 1 : $attribute->publish = 0;
			$attribute->update($request->except('publish'));
		}
		return redirect('admin/attributes')->with('done', 'Atribut je izmenjen');
	}

	public function updateLang(Request $request, $id)
	{
		$request->input('locale')? $locale = $request->input('locale') : $locale = 'sr';
		app()->setLocale($locale);
		$attribute = Attribute::find($id);
        $attribute->title = $request->input('title');
		if($attribute){
			$attribute->update($request->except('publish'));
		}
		return redirect('admin/attributes')->with('done', 'Atribut je izmenjen');
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
		Attribute::find($id)->delete();
		return redirect('admin/attributes')->with('done', 'Atribut je obrisan.');
	}

	public function publish($id){
		$val = Input::get('val');
		if($val == 'true'){ $publish = 1; }else{ $publish = 0; }
		$attribute = Attribute::find($id)->update(array('publish' => $publish));
		if(isset($attribute)){ return 'da'; }else{ return 'ne'; }
	}

	public function sortable($id)
	{
		$slug = 'products';
		$property = Property::find($id);
		return view('admin.attributes.sortable', compact('slug', 'osobina'));
	}

	public function sortable_ajax()
	{
		$sort = Input::get('sortable');
		Attribute::save_att_order($sort);
		return 'save';
	}

	public function search(Request $request){
		$title = $request->input('title');
		$attributes = Attribute::where('title', 'LIKE', '%'.$title.'%')->get();
		return view('admin.attributes.index_append', compact('attributes'));
	}

	public function removeSearch(){
		return redirect('admin/attributes');
	}

	public function block($id){
	    return $id;
    }

}
