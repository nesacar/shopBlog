<?php

namespace App\Http\Controllers;

use App\Block;
use App\Box;
use App\Language;
use App\Http\Requests\CreateBlockRequest;
use Illuminate\Http\Request;
use File;

class BlocksController extends Controller
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
        $slug = 'blocks';
        $blocks = Block::orderby('created_at', 'DESC')->paginate(50);
        return view('admin.blocks.index', compact('blocks','slug'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $slug = 'blocks';
        return view('admin.blocks.create', compact('slug'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBlockRequest $request)
    {
        $block = Block::create($request->all());
        $request->input('publish')? $block->publish = 1 : $block->publish = 0;
        $block->update();
        return redirect('admin/blocks')->with('done', 'Šablon je kreiran.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function show(Block $block)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function edit(Block $block)
    {
        $slug = 'products';
        $boxes = $block->box;
        return view('admin.blocks.edit', compact('slug', 'block', 'boxes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function update(CreateBlockRequest $request, Block $block)
    {
        $block->update($request->all());
        $request->input('publish')? $block->publish = 1 : $block->publish = 0;
        $block->update();
        return redirect('admin/blocks')->with('done', 'Šablon je izmenjen.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function destroy(Block $block)
    {
        //
    }

    public function delete($id)
    {
        $block = Block::find($id);
        $boxes = Box::where('block_id', $block->id)->get();
        if(count($boxes)>0){
            foreach ($boxes as $box){
                File::delete($box->image);
                $box->delete();
            }
        }
        $block->delete();
        return redirect('admin/blocks')->with('done', 'Šablon je obrisan.');
    }

    public function publish(Request $request, $id){
        $val = $request->input('val');
        if($val == 'true'){ $publish = 1; }else{ $publish = 0; }
        $block = Block::find($id)->update(array('publish' => $publish));
        if(isset($block)){ return 'da'; }else{ return 'ne'; }
    }
}
