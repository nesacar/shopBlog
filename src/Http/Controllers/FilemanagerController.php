<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilemanagerController extends Controller
{
    public function __construct(){
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
        return view('admin.posts.filemanager');
    }
}
