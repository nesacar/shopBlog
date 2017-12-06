<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\PCategory;
use App\PCategoryTranslation;
use App\Http\Requests\CreateCategoriesRequest;
use App\Http\Requests\UpdateCategoryLangRequest;
use App\Language;
use App\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use File;
use DB;
use Session;

class CategoriesController extends Controller
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
        $slug = 'categories';
        app()->setLocale('sr');
        Session::get('category_id')? $category_id = Session::get('category_id') : $category_id = 0;
        if($category_id == 0){
            $categories = Category::orderby('order', 'ASC')->paginate(Category::$list_limit);
        }else{
            $categories = Category::where('parent', $category_id)->orderby('order', 'ASC')->paginate(Category::$list_limit);
        }
        $cats = Category::join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('categories.level', '<', 4)->where('categories.publish', 1)->where('category_translations.locale', app()->getLocale())
            ->orderBy('categories.order', 'ASC')
            ->pluck('category_translations.title', 'categories.id')->prepend('Sve kategorije', 0);
        return view('admin.categories.index', compact('slug', 'categories', 'cats', 'category_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $slug = 'categories';
        app()->setLocale('sr');
        $catids = [];
        $cats = Category::orderby('order', 'ASC')->get();
        $c = Category::join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('categories.publish', 1)->where('categories.level', '<', 3)
            ->pluck('category_translations.title', 'categories.id')->toArray();
        if(count($c)){
            $catids = $c;
        }
        $catids;
        $brands = Brand::join('brand_translations', 'brands.id', '=', 'brand_translations.brand_id')
            ->where('brands.publish', 1)->pluck('brand_translations.title', 'brands.id')->prepend('Bez brenda...', 0);
        return view('admin.categories.create', compact('slug', 'parents', 'catids', 'cats', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoriesRequest $request)
    {
        app()->setLocale('sr');
        $category = Category::create($request->all());
        $category->title = $request->input('title');
        $category->slug = str_slug($request->input('title'));
        $category->desc = $request->input('desc');
        $request->input('publish')? $category->publish = 1 : $category->publish = 0;
        $request->input('parent')? $category->parent = $request->input('parent') : $category->parent = 0;
        $request->input('level')? $category->parent = $request->input('level') : $category->level = 1;

        if($request->hasFile('image')){
            $imageName = $category->slug . '-' . $category->id . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = 'images/categories/featured/'.$imageName;
            $request->file('image')->move(base_path() . '/public/images/categories/featured/', $imageName);
            $category->image = $imagePath;
        }

        $category->update();
        return redirect('admin/categories')->with('done', 'Kategorija je kreirana.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function show(PCategory $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        app()->setLocale('sr');
        $slug = 'categories';
        $cats = Category::join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('categories.publish', 1)->pluck('category_translations.title', 'categories.id')->prepend('Bez kategorije...', 0);
        $catids = array($category->parent);
        $languages = Language::where('publish', 1)->orderBy('order', 'ASC')->get();
        $brands = Brand::join('brand_translations', 'brands.id', '=', 'brand_translations.brand_id')
            ->where('brands.publish', 1)->pluck('brand_translations.title', 'brands.id')->prepend('Bez brenda...', 0);
        return view('admin.categories.edit', compact('slug', 'category', 'parents', 'languages', 'cats', 'catids', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->input('locale')? $locale = $request->input('locale') : $locale = 'sr';
        $category->brand_id = $request->input('brand_id');
        $category->update($request->all());
        $request->input('publish')? $category->publish = 1 : $category->publish = 0;

        if($request->hasFile('image')){
            $imageName = $category->translate('sr')->slug . '-' . $category->id . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = 'images/categories/featured/'.$imageName;
            $request->file('image')->move(base_path() . '/public/images/categories/featured/', $imageName);
            $category->image = $imagePath;
        }

        $category->update();

        return redirect('admin/categories/'.$category->id.'/edit')->with('done', 'Kategorija je izmenjeno');
    }

    public function updateLang(UpdateCategoryLangRequest $request, $id)
    {
        $request->input('locale')? $locale = $request->input('locale') : $locale = 'sr';
        app()->setLocale($locale);
        $cat = Category::find($id);

        if(isset($cat)){
            $cat->title = $request->input('title');
            $cat->slug = str_slug($request->input('title'));
            $cat->desc = $request->input('desc');
        }
        $cat->update();

        return redirect('admin/categories/'.$id.'/edit')->with('done', 'Kategorija je izmenjena.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(PCategory $category)
    {
        //
    }

    public function delete($id)
    {
        if(\Auth::user()->role >= 2){
            $category = Category::find($id);
            File::delete($category->image);
            $category->delete();
            return redirect('admin/categories')->with('done', 'Kategorija je obrisana.');
        }else{
            return redirect('admin/categories')->with('done', 'Samo admin može obrisati kategoriju.');
        }
    }

    public function publish($id){
        $val = Input::get('val');
        if($val == 'true'){ $primary = 1; }else{ $primary = 0; }
        $category = Category::find($id)->update(array('publish' => $primary));
        if(isset($category)){ return 'da'; }else{ return 'ne'; }
    }

    public function sortable()
    {
        app()->setLocale('sr');
        $slug = 'categories';
        return view('admin.categories.sortable', compact('slug'));
    }

    public function sortableUpdate()
    {
        $sort = Input::get('sortable');
        Category::save_cat_order($sort);
        return 'save';
    }

    public function deleteImg($id){
        $category = Category::find($id);
        File::delete($category->image);
        $category->update(array('image' => null));
        return view('admin.categories.image_append');
    }

    public function search(Request $request, $id){
        Session::put('category_id', $request->input('category_id'));
        return redirect('admin/categories');
    }

    public function properties($id){
        app()->setLocale('sr');
        $slug = 'categories';
        $category = Category::find($id);
        $properties = Property::where('publish', 1)->orderBy('order', 'ASC')->get();
        $ids = $category->property()->pluck('property_id')->toArray();
        return view('admin.categories.properties', compact('slug', 'category', 'properties', 'ids'));
    }

    public function propertiesPost(Request $request, $id){
        $category = Category::find($id);
        $request->input('properties')? $category->property()->sync($request->input('properties')) : $category->property()->sync([]);
        return redirect()->back()->with('done', 'Sačuvano');
    }
}
