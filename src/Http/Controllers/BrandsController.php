<?php namespace App\Http\Controllers;

use App\Asortiman;
use App\Block;
use App\Brand;
use App\Brend;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Language;
use App\Property;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandsController extends Controller {

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
		$slug = 'products';
		$brands = Brand::orderby('order', 'ASC')->paginate(50);
		return view('admin.brands.index', compact('brands','slug'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$slug = 'products';
		return view('admin.brands.create', compact('slug'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreateBrandRequest $request)
	{
	    app()->setLocale('sr');
		$brand = Brand::create($request->all());
		$brand->title = $request->input('title');
		$brand->slug = str_slug($request->input('title'));
		$brand->short = $request->input('short');
		$brand->body = $request->input('body');
		$brand->body2 = $request->input('body2');

		if($request->file('logo')){
			$imageName = $brand->slug.'-'.$brand->id . '.' . $request->file('logo')->getClientOriginalExtension();
			$imagePath = 'images/brands/'.$imageName;
			$request->file('logo')->move(base_path() . '/public/images/brands/', $imageName);
			$brand->logo = $imagePath;
		}

        if($request->file('image')){
            $imageName = 'large-' . $brand->slug.'-'.$brand->id . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = 'images/brands/'.$imageName;
            $request->file('image')->move(base_path() . '/public/images/brands/', $imageName);
            $brand->image = $imagePath;
        }

		$request->input('publish')? $brand->publish = 1 : $brand->publish = 0;
		$brand->update();
		return redirect('admin/brands')->with('done', 'Brend je kreiran.');
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
		$brand = Brand::find($id);
		$slug = 'products';
		$languages = Language::where('publish', 1)->orderBy('order', 'ASC')->get();
		return view('admin.brands.edit', compact('slug', 'brand', 'asortiman', 'languages'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$brand = Brand::find($id);

		if($request->file('logo')){
			$imageName = $brand->slug.'-'.$brand->id . '.' . $request->file('logo')->getClientOriginalExtension();
			$imagePath = 'images/brands/'.$imageName;
			$request->file('logo')->move(base_path() . '/public/images/brands/', $imageName);
			$brand->logo = $imagePath;
		}

        if($request->file('image')){
            $imageName = 'large-' . $brand->slug.'-'.$brand->id . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = 'images/brands/'.$imageName;
            $request->file('image')->move(base_path() . '/public/images/brands/', $imageName);
            $brand->image = $imagePath;
        }

		$request->input('publish')? $brand->publish = 1 : $brand->publish = 0;
		$brand->update();
		return redirect('admin/brands/'.$brand->id.'/edit')->with('done', 'Brend je izmenjen.');
	}

    public function updateLang(Requests\CreateBrandRequest $request, $id)
    {
        $request->input('locale')? $locale = $request->input('locale') : $locale = 'sr';
        app()->setLocale($locale);
        $brand = Brand::find($id);
        $brand->title = $request->input('title');
        $brand->slug = str_slug($request->input('title'));
        $brand->short = $request->input('short');
        $brand->body = $request->input('body');
        $brand->body2 = $request->input('body2');

        $brand->update();

        return redirect('admin/brands/'.$brand->id.'/edit')->with('done', 'Brend je izmenjen.');
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
		Brand::find($id)->delete();
		return redirect('admin/brands')->with('done', 'Brend je obrisan.');
	}

	public function publish($id){
		$val = Input::get('val');
		if($val == 'true'){ $publish = 1; }else{ $publish = 0; }
		$brand = Brand::find($id)->update(array('publish' => $publish));
		if(isset($brand)){ return 'da'; }else{ return 'ne'; }
	}

	public function deleteimg($id)
	{
		$brand = Brand::find($id);
		File::delete($brand->logo);
		$brand->logo = '';
		$brand->update();
		return view('admin.brands.image_append');
	}

	public function attribute($id){
        $slug = 'products';
        $brand = Brand::find($id);
        $collection = Property::find(12);
        $ids = $brand->attribute->pluck('id')->toArray();
        return view('admin.brands.attributes', compact('slug', 'brand', 'collection', 'ids'));
    }

    public function attributeUpdate($id, Request $request){
        $brand = Brand::find($id);
        if(count($request->input('attribute'))>0){
            $brand->attribute()->sync($request->input('attribute'));
        }else{
            $brand->attribute()->sync([]);
        }
        return redirect()->back()->with('done', 'Kolekcije su izmenjene');
    }

    public function block($id){
        $brand = Brand::find($id);
        $collection = Block::where('title', $brand->title)->first();
        if(!empty($collection)){
            return redirect('admin/blocks/'.$collection->id.'/edit');
        }else{
            return redirect('admin/blocks/create')->with('done', 'kreirajte šablon sa nazivom '. $brand->title);
        }
    }

}
