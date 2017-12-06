<?php

namespace App\Http\Controllers;

use App\Block;
use App\Box;
use App\Http\Requests\CreateBoxRequest;
use App\Language;
use App\Http\Requests\CreateBlockRequest;
use Illuminate\Http\Request;
use File;

class BoxesController extends Controller
{
    public function __construct(){
        $this->middleware('menager');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        app()->setLocale('sr');
        $slug = 'blocks';
        $boxes = Box::orderby('created_at', 'DESC')->paginate(50);
        return view('admin.boxes.index', compact('boxes','slug'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $slug = 'blocks';
        $blocks = Block::where('publish', 1)->orderBy('title', 'ASC')->pluck('title', 'id');
        if(count($blocks) == 0) return redirect('admin/boxes')->with('error', 'Kreirajte provo šablon');
        $request->input('block_id')? $block_id = $request->input('block_id') : $block_id = null;
        return view('admin.boxes.create', compact('slug', 'block_id', 'blocks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBoxRequest $request)
    {
        app()->setLocale('sr');
        $box = Box::create($request->all());
        $box->title = $request->input('title');
        $box->subtitle = $request->input('subtitle');
        $box->button = $request->input('button');
        $box->link = $request->input('link');

        if($request->hasFile('image')){
            $imageName = str_slug($box->title). '-' . $box->id . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = 'images/blocks/'.$imageName;
            $request->file('image')->move(base_path() . '/public/images/blocks/', $imageName);
            $box->image = $imagePath;
        }

        $request->input('publish')? $box->publish = 1 : $box->publish = 0;
        $box->update();
        return redirect('admin/boxes')->with('done', 'Šablon je kreiran.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function show(Box $box)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function edit(Box $box)
    {
        $slug = 'blocks';
        $blocks = Block::where('publish', 1)->orderBy('title', 'ASC')->pluck('title', 'id');
        if(count($blocks) == 0) return redirect('admin/boxes')->with('error', 'Kreirajte provo šablon');
        $languages = Language::where('publish', 1)->orderBy('order', 'ASC')->get();
        return view('admin.boxes.edit', compact('slug', 'box', 'blocks', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Box $box)
    {
        $box->update($request->all());

        if($request->hasFile('image')){
            $imageName = str_slug($box->title). '-' . $box->id . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = 'images/blocks/'.$imageName;
            $request->file('image')->move(base_path() . '/public/images/blocks/', $imageName);
            $box->image = $imagePath;
        }

        $request->input('publish')? $box->publish = 1 : $box->publish = 0;
        $box->update();
        return redirect('admin/boxes/'.$box->id.'/edit')->with('done', 'Šablon je izmenjen.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function updateLang(CreateBoxRequest $request, $id)
    {
        $request->input('locale')? $locale = $request->input('locale') : $locale = 'sr';
        app()->setLocale($locale);
        $box = Box::find($id);
        $box->title = $request->input('title');
        $box->subtitle = $request->input('subtitle');
        $box->button = $request->input('button');
        $box->link = $request->input('link');
        $box->update();
        return redirect('admin/boxes/'.$box->id.'/edit')->with('done', 'Šablon je izmenjen.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function destroy(Box $box)
    {
        //
    }

    public function delete($id)
    {
        $box = Box::find($id);
        File::delete($box->image);
        $box->delete();
        return redirect('admin/boxes')->with('done', 'Šablon je obrisan.');
    }

    public function publish(Request $request, $id){
        $val = $request->input('val');
        if($val == 'true'){ $publish = 1; }else{ $publish = 0; }
        $box = Box::find($id)->update(array('publish' => $publish));
        if(isset($box)){ return 'da'; }else{ return 'ne'; }
    }
}
