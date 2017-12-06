<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditTagRequest;
use App\Language;
use App\Tag;
use App\TagTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagsController extends Controller
{
    private static $num = 50;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slug = 'posts';
        app()->setLocale('sr');
        $tags = Tag::select('tags.id', 'tag_translations.title')->join('tag_translations', 'tags.id', '=', 'tag_translations.tag_id')
            ->where('tag_translations.locale', 'sr')->orderBy('tag_translations.title', 'ASC')->paginate(self::$num);
        $sum = Tag::count();
        return view('admin.tags.index',compact('tags', 'slug', 'sum'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function create(Tag $tag)
    {
        $slug = 'posts';
        return view('admin.tags.create', compact('tag', 'slug'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function store(EditTagRequest $request)
    {
        app()->setLocale('sr');
        $tag = Tag::create($request->all());
        $tag->slug = Str::slug($request->input('title'));
        $tag->update();
        return redirect('admin/tags')->with('done', 'Tag je kreiran.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        $slug = 'posts';
        $languages = Language::where('publish', 1)->orderBy('order', 'ASC')->get();
        return view('admin.tags.edit', compact('tag', 'slug', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(EditTagRequest $request, Tag $tag)
    {
        app()->setLocale('sr');
        $tag->slug = Str::slug($request->input('name'));
        $tag->update($request->all());
        return redirect('admin/tags');
    }

    public function updateLang($id, EditTagRequest $request)
    {
        $request->input('locale')? $locale = $request->input('locale') : $locale = 'sr';
        app()->setLocale($locale);
        $tag = Tag::findOrFail($id);
        $tag->title = $request->input('title');
        $tag->slug = Str::slug($request->input('title'));
        $tag->update();
        return redirect('admin/tags/'.$tag->id.'/edit')->with('done', 'Tag je izmenjen');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
        $tag = Tag::find($id);
        $tag->delete();
        TagTranslation::where('tag_id', $tag->id)->delete();
        return redirect('admin/tags');
    }
}
