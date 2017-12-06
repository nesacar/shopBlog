<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Images;
use App\Logic\Image\ImageRepository;
use App\Osobina;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Illuminate\Foundation\Bus\DispatchesCommands;

class ImageController extends Controller {

	protected $image;

	public function __construct(ImageRepository $imageRepository)
	{
		$this->image = $imageRepository;
		$this->middleware('menager');
	}

	public function getUpload()
	{
		return view('pages.upload');
	}

	public function postUpload(Request $request)
	{
	    app()->setLocale('sr');
		$product = Product::find($request->input('product_id'));

		if(isset($product->images) && count($product->images) > 0){
			$last = $product->images->last();
			$i = Images::getNum($last->file_name);
		}else{
			$i=0;
		}
		if ($request->hasFile('file')) {
			$file = $request->file('file');

			$i++;
			$slug = Str::slug($product->title);
			$slug2 = $slug.'-'.$product->id;
			$filename = $slug.'-'.$i.'.'.$file->getClientOriginalExtension();
			$small_filename = $slug.'-'.$i.'_tmb.'.$file->getClientOriginalExtension();
			$file->move('images/galleries/'.$slug2, $filename);

			Images::create([
				'product_id' => $request->input('product_id'),
				'file_name' => $filename,
				'file_size' => $file->getClientSize(),
				'file_mime' => $file->getClientMimeType(),
				'file_path' => 'images/galleries/'.$slug2.'/'.$filename
			]);
		}
	}

	public function deleteUpload()
	{

		$filename = Input::get('id');

		if(!$filename)
		{
			return 0;
		}

		$response = $this->image->delete( $filename );

		return $response;
	}

}
