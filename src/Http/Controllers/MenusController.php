<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CreateMenuImageRequest;
use App\Http\Requests\CreateMenuRequest;
use App\Language;
use App\Menu;
use App\MenuImage;
use App\MenuLink;
use App\PCategory;
use App\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use File;

class MenusController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slug = 'settings';
        $menus = Menu::orderBy('created_at', 'DESC')->paginate(Menu::$list_limit);
        return view('admin.menus.index', compact('slug', 'menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $slug = 'settings';
        return view('admin.menus.create', compact('slug'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMenuRequest $request)
    {
        $menu = Menu::create($request->all());
        $menu->slug = str_slug($request->input('title'));
        $request->input('publish')? $menu->publish = 1 : $menu->publish = 0;
        $menu->update();
        return redirect('admin/menus')->with('done', 'Meni je kreiran.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        $slug = 'settings';
        return view('admin.menus.edit', compact('slug', 'menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(CreateMenuRequest $request, Menu $menu)
    {
        $menu->update($request->all());
        $menu->slug = str_slug($request->input('title'));
        $request->input('publish')? $menu->publish = 1 : $menu->publish = 0;
        $menu->update();
        return redirect('admin/menus')->with('done', 'Meni je izmenjen.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //
    }

    public function delete($id)
    {
        if(\Auth::user()->role >= 3){
            $menu = Menu::find($id);
            $menuLinks = MenuLink::where('menu_id', $menu->id)->get();
            if(count($menuLinks)>0){
                foreach ($menuLinks as $links){
                    File::delete($links->image);
                    $links->delete();
                }
            }
            $menu->delete();
            return redirect('admin/menus')->with('done', 'Menu je obrisan.');
        }else{
            return redirect('admin/menus')->with('done', 'Samo admin može obrisati meni.');
        }
    }

    public function publish($id){
        $val = Input::get('val');
        if($val == 'true'){ $primary = 1; }else{ $primary = 0; }
        $menu = Menu::find($id)->update(array('publish' => $primary));
        if(isset($menu)){ return 'da'; }else{ return 'ne'; }
    }

    public function editLinks($id){
        app()->setLocale('sr');
        $slug = 'settings';
        $menu = Menu::find($id);
        $excludes = MenuLink::where('menu_id', $menu->id)->where('locale', 'sr')->pluck('cat_id');

        $links = $menu->menuLinks()->where('parent', 0)->where('level', 1)->orderBy('order', 'ASC')->get();

        $categories = Category::where('publish', 1)
            ->whereNotIn('id', $excludes)->orderBy('order', 'ASC')->get();

        $pcategories = PCategory::where('publish', 1)
            ->whereNotIn('id', $excludes)->orderBy('order', 'ASC')->get();

        $custom = 1000;
        $res = MenuLink::orderBy('cat_id', 'DESC')->first();
        if(isset($res)){
            $custom = $res->cat_id + 1;
        }

        return view('admin.menus.editLinks', compact('slug', 'menu', 'categories', 'pcategories', 'links', 'excludes', 'custom'));
    }

    public function editLinksUpdate(Request $request, $id){
        $request->input('locale')? $locale = $request->input('locale') : $locale = 'sr';
        app()->setLocale($locale);
        $menu = Menu::find($id);
        $sort = $request->input('sortable');
        $ids = $request->input('ids');
        $types = $request->input('types');
        $attributes = $request->input('attributes');
        $attributes = MenuLink::setAttributes($attributes);


        $links = $links = MenuLink::where('menu_id', $id)->get();

        MenuLink::deleteLinks($menu);


        if(count($ids)>0){
            for($i=0;$i<count($ids);$i++){
                $tt = MenuLink::getTitle($links, $ids[$i]);
                if($types[$i] == 1){ //shop kategorija
                    $category = Category::find($ids[$i]);
                    isset($tt)? $title = $tt : $title = $category->title;
                    $l = MenuLink::create([
                        'menu_id' => $menu->id,
                        'cat_id' => $category->id,
                        'title' => $title,
                        'type' => $types[$i],
                        'locale' => $locale,
                        'publish' => 1
                    ]);
                    if(count($attributes[$i])>0){
                        $l->attribute()->sync($attributes[$i]);
                    }
                }else if($types[$i] == 2){ //post kategorija
                    $pcategory = PCategory::find($ids[$i]);
                    isset($tt)? $title = $tt : $title = $pcategory->title;
                    $l = MenuLink::create([
                        'menu_id' => $menu->id,
                        'cat_id' => $pcategory->id,
                        'title' => $title,
                        'type' => $types[$i],
                        'locale' => $locale,
                        'publish' => 1
                    ]);
                    if(count($attributes[$i])>0){
                        $l->attribute()->sync($attributes[$i]);
                    }
                }else{ //custom link
                    isset($tt)? $title = $tt : $title = 'Custom link';
                    $l = MenuLink::create([
                        'menu_id' => $menu->id,
                        'cat_id' => $ids[$i],
                        'title' => $title,
                        'type' => $types[$i],
                        'locale' => $locale,
                        'publish' => 1
                    ]);
                    if(count($attributes[$i])>0){
                        $l->attribute()->sync($attributes[$i]);
                    }
                }
            }
        }


        $menu_ids = MenuLink::where('menu_id', $menu->id)->pluck('id');

        MenuLink::save_cat_order($menu_ids, $sort);
        MenuLink::setLinks($menu, $links);
        MenuLink::addSufix();

        return 'save';
    }

    public function editLink($id){
        app()->setLocale('sr');
        $slug = 'settings';
        $link = MenuLink::find($id);
        if(empty($link)){
            return redirect()->back()->with('error', 'Pokusajte ponovo');
        }
        $menu = $link->menu;
        if($link->type == 1){
            $category = Category::find($link->cat_id);
            $properties = $category->property()->where('publish', 1)->orderBy('order', ' asc')->get();
            $ids = $link->attribute->pluck('id')->toArray();
        }else{
            $properties = Property::where('publish', 1)->orderBy('order', ' asc')->get();
            $ids = $link->attribute->pluck('id')->toArray();
        }
        $languages = Language::where('publish', 1)->orderBy('order', 'ASC')->get();
        return view('admin.menus.editLink', compact('slug', 'menu', 'link', 'ids', 'properties', 'languages'));
    }

    public function editLinkUpdate(Request $request, $id){
        app()->setLocale('sr');
        $link = MenuLink::find($id);
        $link->title = $request->input('title');
        $link->link = $request->input('link');
        $link->sufix = $request->input('sufix');
        $link->desc = $request->input('desc');

        if($request->hasFile('image')){
            $imageName = $link->id . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = 'images/menus/'.$imageName;
            $request->file('image')->move(base_path() . '/public/images/menus/', $imageName);
            $link->image = $imagePath;
        }

        $link->update();
        return redirect('admin/menus/'.$link->id.'/editLink');
    }

    public function menuLinksAttributesPost(Request $request, $id){
        $link = MenuLink::find($id);
        $request->input('attributes')? $link->attribute()->sync($request->input('attributes')) : $link->attribute()->sync([]);
        $link->sufix = MenuLink::addSufix($id);
        $link->update();
        return redirect()->back()->with('done', 'Izmenjeno');
    }

    /********* IMAGES ******/
    public function images($id){
        app()->setLocale('sr');
        $slug = 'settings';
        $menu = Menu::find($id);
        $images = MenuImage::where('menu_id', $menu->id)->orderBy('created_at', 'DESC')->paginate(Menu::$list_limit);
        return view('admin.menus.images', compact('slug', 'menu', 'images'));
    }

    public function createImage($id){
        app()->setLocale('sr');
        $slug = 'settings';
        $menu = Menu::find($id);
        return view('admin.menus.createImage', compact('slug', 'menu'));
    }

    public function storeImage(CreateMenuImageRequest $request, $id){
        app()->setLocale('sr');
        $image = new MenuImage();
        $image->menu_id = $request->input('menu_id');
        $image->title = $request->input('title');
        $image->button = $request->input('button');
        $image->link = $request->input('link');

        if($request->hasFile('image')){
            $imageName = $image->id . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = 'images/menus/'.$imageName;
            $request->file('image')->move(base_path() . '/public/images/menus/', $imageName);
            $image->image = $imagePath;
        }

        $request->input('publish')? $image->publish = $request->input('publish') : $image->publish = 0;
        $image->save();

        return redirect('admin/menus/'.$image->menu_id.'/images')->with('done', 'Slika menija je kreirana');
    }

    public function editImage($id){
        app()->setLocale('sr');
        $slug = 'settings';
        $image = MenuImage::find($id);
        $menu = Menu::find($image->menu_id);
        $languages = Language::where('publish', 1)->orderBy('order', 'ASC')->get();
        return view('admin.menus.editImage', compact('slug', 'menu', 'languages', 'image'));
    }

    public function updateImage(Request $request, $id){
        $image = MenuImage::find($id);
        $image->order = $request->input('order');
        $request->input('publish')? $image->publish = $request->input('publish') : $image->publish = 0;

        if($request->hasFile('image')){
            $imageName = $image->id . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = 'images/menus/'.$imageName;
            $request->file('image')->move(base_path() . '/public/images/menus/', $imageName);
            $image->image = $imagePath;
        }

        $image->update();
        return redirect('admin/menus/'.$image->id.'/editImage')->with('done', 'Slika menija je izmenjena');
    }

    public function updateLangImage(CreateMenuImageRequest $request, $id){
        $request->input('locale')? $locale = $request->input('locale') : $locale = 'sr';
        app()->setLocale($locale);
        $image = MenuImage::find($id);
        $image->title = $request->input('title');
        $image->button = $request->input('button');
        $image->link = $request->input('link');
        $image->update();
        return redirect('admin/menus/'.$image->id.'/editImage')->with('done', 'Slika menija je izmenjena');
    }

    public function deleteImage($id){
        $image = MenuImage::find($id);
        File::delete($image->image);
        $image->delete();
        return redirect('admin/menus/'.$image->menu_id.'/images')->with('done', 'Slika menija je obrisana');
    }

    public function publishImage($id){
        $val = Input::get('val');
        if($val == 'true'){ $primary = 1; }else{ $primary = 0; }
        $image = MenuImage::find($id)->update(array('publish' => $primary));
        if(isset($image)){ return 'da'; }else{ return 'ne'; }
    }

    /***** END IMAGES ******/
}
