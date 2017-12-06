<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateThemeRequest;
use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

class ThemesController extends Controller
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
        $themes = Theme::all();
        return view('admin.themes.index', compact('themes', 'slug'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $slug = 'settings';
        return view('admin.themes.create', compact('slug'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateThemeRequest $request)
    {
        $theme = Theme::create($request->all());
        $theme->slug = Str::slug($request->input('title'));
        $theme->update();
        return redirect('admin/themes')->with('done', 'Tema je kreirana.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function show(Theme $theme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function edit(Theme $theme)
    {
        $slug = 'settings';
        return view('admin.themes.edit', compact('theme', 'slug'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function update(CreateThemeRequest $request, Theme $theme)
    {
        $theme->title = $request->input('title');
        $theme->slug = Str::slug($request->input('title'));
        $theme->version = $request->input('version');
        $theme->author = $request->input('author');
        $theme->author_address = $request->input('author_address');
        $theme->author_email = $request->input('author_email');
        $theme->developer = $request->input('developer');

        if($request->file('image')){
            $imageName = $theme->slug . $theme->id . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = 'images/themes/'.$imageName;
            $request->file('image')->move(base_path() . '/public/images/themes/', $imageName);
            $theme->image = $imagePath;
        }

        $theme->update();
        return redirect('admin/themes')->with('done', 'Tema je izmenjena.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function destroy(Theme $theme)
    {
        //
    }

    public function delete(Theme $theme)
    {
        File::delete($theme->image);
        $theme->delete();
        return redirect('admin/themes')->with('done', 'Tema je izbrisana.');
    }

    public function deleteimg($id)
    {
        $theme = Theme::find($id);
        File::delete($theme->image);
        $theme->update(array('image' => ''));
        return view('admin.themes.append-img');
    }

    public function activate($id){
        Theme::activateTheme($id);
        return redirect('admin/themes')->with('done', 'Tema je aktivirana.');
    }

    public function deactivate($id){
        Theme::deactivateTheme($id);
        return redirect('admin/themes')->with('done', 'Tema je deaktivirana.');
    }
}
