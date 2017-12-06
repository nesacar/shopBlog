<?php

namespace App\Http\Controllers;

use App\Language;
use App\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
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
        $slug = 'settings';
        $settings = Setting::first();
        if(isset($settings)){
            return redirect('admin/settings/'.$settings->id.'/edit', compact('slug'));
        }else{
            return redirect('admin/settings/create', compact('slug'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        app()->setLocale('sr');
        $slug = 'settings';
        $settings = Setting::first();
        if(count($settings)){
            return view('admin.settings.edit', compact('settings', 'slug'));
        }else{
            return view('admin.settings.create', compact('slug'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $setting = Setting::create($request->all());
        $request->input('blog')? $setting->blog = 1 : $setting->blog = 0;
        $request->input('shop')? $setting->shop = 1 : $setting->shop = 0;
        $request->input('colorDependence')? $setting->colorDependence = 1 : $setting->colorDependence = 0;
        $request->input('materialDependence')? $setting->materialDependence = 1 : $setting->materialDependence = 0;
        $request->input('newsletter')? $setting->newsletter = 1 : $setting->newsletter = 0;
        $setting->update();
        return redirect('admin/settings/'.$setting->id.'/edit');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        $slug = 'settings';
        $langs = Language::where('publish', 1)->orderBy('order', 'ASC')->pluck('fullname', 'id');
        $languages = Language::where('publish', 1)->orderBy('order', 'ASC')->get();
        return view('admin.settings.edit', compact('setting', 'slug', 'langs', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        $setting->update($request->all());
        $setting->language_id = $request->input('language_id');
        $request->input('blog')? $setting->blog = 1 : $setting->blog = 0;
        $request->input('shop')? $setting->shop = 1 : $setting->shop = 0;
        $request->input('colorDependence')? $setting->colorDependence = 1 : $setting->colorDependence = 0;
        $request->input('materialDependence')? $setting->materialDependence = 1 : $setting->materialDependence = 0;
        $request->input('newsletter')? $setting->newsletter = 1 : $setting->newsletter = 0;
        $setting->update();

        return redirect('admin/settings/'.$setting->id.'/edit')->with('done', 'Podešavanja su izmenjena.');
    }

    public function updateLang(Request $request, $id)
    {
        $request->input('locale')? $locale = $request->input('locale') : $locale = 'sr';
        app()->setLocale($locale);
        $settings = Setting::find($id);
        if($settings){
            $settings->title = $request->input('title');
            $settings->keywords = $request->input('keywords');
            $settings->desc = $request->input('desc');
            $settings->footer = $request->input('footer');
            $settings->update();

            $settings->translate($locale)->title = $request->input('title');
            $settings->translate($locale)->keywords = $request->input('keywords');
            $settings->translate($locale)->desc = $request->input('desc');
            $settings->translate($locale)->footer = $request->input('footer');
            $settings->update();
        }
        return redirect('admin/settings/'.$settings->id.'/edit')->with('done', 'Podešavanja su izmenjena');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
