<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLanguageRequest;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LanguagesController extends Controller
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
        $slug = 'languages';
        $languages = Language::orderby('order', 'ASC')->paginate(Language::$list_limit);
        return view('admin.languages.index', compact('slug', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $slug = 'languages';
        return view('admin.languages.create', compact('slug'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLanguageRequest $request)
    {
        $language = Language::create($request->all());
        $request->input('publish')? $language->publish = 1 : $language->publish = 0;
        $language->update();
        return redirect('admin/languages')->with('done', 'Jezik je kreiran. U config/laravellocalization uključite napravljen jezik');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language)
    {
        $slug = 'languages';
        return view('admin.languages.edit', compact('slug', 'language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(CreateLanguageRequest $request, Language $language)
    {
        $language->update($request->all());
        $language->order = $request->input('order');
        $request->input('publish')? $language->publish = 1 : $language->publish = 0;
        $language->update();
        return redirect('admin/languages')->with('done', 'Jezik je izmenjen.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        //
    }

    public function delete($id)
    {
        if(\Auth::user()->role >= 2){
            $lang = Language::find($id);
            if(isset($lang) && $lang->primary == false){
                $lang->delete();
                return redirect('admin/languages')->with('done', 'Jezik je obrisan.');
            }else{
                return redirect('admin/languages')->with('error', 'Primarni jezik ne može biti obrisan.');
            }
        }else{
            return redirect('admin/languages')->with('done', 'Samo admin može obrisati jezik.');
        }
    }

    public function publish($id){
        $val = Input::get('val');
        if($val == 'true'){ $primary = 1; }else{ $primary = 0; }
        $language = Language::find($id)->update(array('publish' => $primary));
        if(isset($language)){ return 'da'; }else{ return 'ne'; }
    }
}
