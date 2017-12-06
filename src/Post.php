<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;

class Post extends Model
{
    use Translatable;

    public static $list_limit = 50;

    public $translatedAttributes = ['title', 'slug', 'short', 'body'];

    protected $table = 'posts';

    protected $fillable = ['id', 'user_id', 'image' , 'tmb', 'home', 'publish_at', 'publish'];

    public static function getLastsPost($limit){
        return self::select('posts.*')->join('categories', 'posts.category_id', '=', 'categories.id')
            ->where('categories.slug', '<>', 'info')->where('categories.publish', 1)->where('categories.blog', 1)
            ->where('posts.publish', 1)->where('posts.publish_at', '<=', (new \Carbon\Carbon()))->orderby('posts.publish_at', 'DESC')->take($limit)->get();
    }

    public static function getRelatedPost($category, $post, $limit=2){
        if(isset($category) && $post->id != 8){ //uslovi korišćenja
            if($category->slug != 'info') {
                return $category->post()->where('publish', 1)->where('publish_at', '<=', (new \Carbon\Carbon()))->where('id', '<>', $post->id)->inRandomOrder()->take($limit)->get();
            }
        }
        return [];
    }

    public static function filteredPosts($s=false,$c=0){
        if($c>0){
            return self::where(function ($query) use ($s){
                if(isset($s)){
                    $query->whereTranslationLike('title', '%'.$s.'%')->orWhereTranslationLike('id', '%'.$s.'%')->orWhereTranslationLike('short', '%'.$s.'%')->orWhereTranslationLike('slug', '%'.$s.'%');
                }
            })->where('posts.p_category_id', $c)->orderby('posts.publish_at', 'DESC')->paginate(self::$list_limit);
        }else{
            return self::whereTranslationLike('title', '%'.$s.'%')->orWhereTranslationLike('id', '%'.$s.'%')->orWhereTranslationLike('short', '%'.$s.'%')
                ->orWhereTranslationLike('slug', '%'.$s.'%')->orderby('posts.publish_at', 'DESC')->paginate(self::$list_limit);
        }
    }

    public static function cropImage1($post=false, $ext){
        if($post){
            if($post->image1 != null && $post->image1 != ''){
                \Image::make($post->image1)->fit(800, 300)->save('images/posts/800x300/'.$post->translate('sr')->slug. '-'. $post->id . '.' .$ext);
            }
            return 'images/posts/800x300/'.$post->translate('sr')->slug. '-'. $post->id . '.' .$ext;
        }
        return false;
    }

    public static function cropImage2($post=false, $ext){
        if($post){
            if($post->image2 != null && $post->image2 != ''){
                \Image::make($post->image2)->fit(294, 294)->save('images/posts/294x294/'.$post->translate('sr')->slug. '-'. $post->id . '.' .$ext);
            }
            return 'images/posts/294x294/'.$post->translate('sr')->slug. '-'. $post->id . '.' .$ext;
        }
        return false;
    }

    public static function getPostLink($post, $locale = 'sr'){
        if(isset($post)){
            if($post->category->parent == 0){
                return url($post->category->{'slug:'.$locale} . '/' . $post->{'slug:'.$locale} . '/' . $post->id);
            }else{
                $cat = PCategory::find($post->category->parent);
                return url($cat->{'slug:'.$locale} . '/' .$post->category->{'slug:'.$locale} . '/' . $post->{'slug:'.$locale} . '/' . $post->id);
            }
        }
        return '';
    }

    public static function frontSearch($text){
        return self::select('posts.*', 'post_translations.title as title', 'post_translations.short as short')->join('post_translations', 'posts.id', '=', 'post_translations.post_id')
            ->where('posts.publish', 1)->where('publish_at', '<=', (new \Carbon\Carbon()))->where('post_translations.locale', app()->getLocale())
            ->where(function ($query) use ($text){
                if(isset($text)){
                    $query->where('post_translations.title', 'like', '%'.$text.'%')->orWhere('post_translations.slug', 'like', '%'.$text.'%')->orWhere('post_translations.short', 'like', '%'.$text.'%');
                }
            })->orderBy('posts.publish_at', 'DESC')->take(12)->get();
    }

    public static function search($text){
        return self::select('posts.*', 'post_translations.title as title', 'post_translations.short as short')->join('post_translations', 'posts.id', '=', 'post_translations.post_id')
            ->where('post_translations.locale', app()->getLocale())
            ->where(function ($query) use ($text){
                if(isset($text)){
                    $query->where('post_translations.title', 'like', '%'.$text.'%')->orWhere('post_translations.slug', 'like', '%'.$text.'%')->orWhere('post_translations.short', 'like', '%'.$text.'%');
                }
            })->orderBy('posts.publish_at', 'DESC')->take(50)->get();
    }

    public static function getNext($post){
        return self::select('posts.*', 'post_translations.title as title', 'post_translations.slug as slug', 'post_translations.short as short', 'post_translations.body as body')
            ->join('post_translations', 'posts.id', '=', 'post_translations.post_id')
            ->join('categories', 'posts.category_id', '=', 'categories.id')->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('post_translations.locale', app()->getLocale())->where('post_translations.slug', '<>', null)
            ->where('categories.id', $post->category_id)->where('category_translations.locale', app()->getLocale())->where('category_translations.slug', '<>', null)
            ->where('posts.publish', 1)->where('posts.publish_at', '>', $post->publish_at)->orderBy('posts.publish_at', 'ASC')->first();
    }

    public static function getPrev($post){
        return self::select('posts.*', 'post_translations.title as title', 'post_translations.slug as slug', 'post_translations.short as short', 'post_translations.body as body')
            ->join('post_translations', 'posts.id', '=', 'post_translations.post_id')
            ->join('categories', 'posts.category_id', '=', 'categories.id')->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('post_translations.locale', app()->getLocale())->where('post_translations.slug', '<>', null)
            ->where('categories.id', $post->category_id)->where('category_translations.locale', app()->getLocale())->where('category_translations.slug', '<>', null)
            ->where('posts.publish', 1)->where('posts.publish_at', '<', $post->publish_at)->orderBy('posts.publish_at', 'DESC')->first();
    }

    public static function getPostSelect($locale='sr'){
        return self::join('post_translations', 'posts.id', '=', 'post_translations.post_id')
            ->where('post_translations.locale', $locale)->where('posts.publish', 1)->where('posts.publish_at', '<=', (new \Carbon\Carbon()))
            ->pluck('post_translations.title', 'posts.id');
    }

    public static function pagePreview($post){
        if($post->publish == 1 && $post->publish_at <= new \Carbon\Carbon()){
            return true;
        }elseif(\Auth::check()){
            return true;
        }else{
            return false;
        }
    }

    public static function isTranslate($post, $locale){
        if(isset($post)){
            $row = PostTranslation::where('post_id', $post->id)->where('locale', $locale)->first();
            if(isset($row)){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public static function getPosts($category=false, $limit=7){
        if($category){
            return $category->post()->select('posts.*', 'post_translations.title as title', 'post_translations.slug as slug', 'post_translations.short as short', 'post_translations.body as body')
                ->join('post_translations', 'posts.id', '=', 'post_translations.post_id')->where('post_translations.locale', app()->getLocale())->where('post_translations.slug', '<>', null)
                ->where('posts.publish', 1)->where('posts.cat', 1)->where('posts.publish_at', '<=', (new \Carbon\Carbon()))->orderBy('posts.publish_at', 'DESC')->paginate($limit);
        }else{
            return self::select('posts.*', 'post_translations.title as title', 'post_translations.slug as slug', 'post_translations.short as short', 'post_translations.body as body')
                ->join('post_translations', 'posts.id', '=', 'post_translations.post_id')->where('post_translations.locale', app()->getLocale())->where('post_translations.slug', '<>', null)
                ->where('posts.home', 1)->where('posts.publish', 1)->where('posts.publish_at', '<=', (new \Carbon\Carbon()))->orderBy('posts.publish_at', 'DESC')->paginate($limit);
        }
    }

    public static function getPostsNews($limit=6){
        return self::select('posts.*', 'post_translations.title as title', 'post_translations.slug as slug', 'post_translations.short as short', 'post_translations.body as body')
            ->join('post_translations', 'posts.id', '=', 'post_translations.post_id')->where('post_translations.locale', app()->getLocale())->where('post_translations.slug', '<>', null)
            ->where('posts.publish', 1)->where('posts.news', 1)->where('posts.publish_at', '<=', (new \Carbon\Carbon()))->orderBy('posts.publish_at', 'DESC')->paginate($limit);
    }

    public static function getSlides($category=false, $limit=6){
        if($category){
            return $category->post()->select('posts.*', 'post_translations.title as title', 'post_translations.slug as slug', 'post_translations.short as short', 'post_translations.body as body')
                ->join('post_translations', 'posts.id', '=', 'post_translations.post_id')->where('post_translations.locale', app()->getLocale())->where('post_translations.slug', '<>', null)
                ->where('slider', 1)->where('posts.publish', 1)->where('posts.publish_at', '<=', (new \Carbon\Carbon()))->orderBy('posts.publish_at', 'DESC')->limit($limit)->get();
        }else{
            return self::select('posts.*', 'post_translations.title as title', 'post_translations.slug as slug', 'post_translations.short as short', 'post_translations.body as body')
                ->join('post_translations', 'posts.id', '=', 'post_translations.post_id')->where('post_translations.locale', app()->getLocale())->where('post_translations.slug', '<>', null)
                ->where('slider', 1)->where('posts.publish', 1)->where('posts.publish_at', '<=', (new \Carbon\Carbon()))->orderBy('posts.publish_at', 'DESC')->limit($limit)->get();
        }
    }

    public static function getSubcategoriesPosts($category, $limit=6){
        $sub = PCategory::where('parent', $category->id)->pluck('id');
        return self::whereIn('category_id', $sub)->where('publish', 1)->where('publish_at', '<=', (new \Carbon\Carbon()))->where('cat', 1)
            ->orderBy('publish_at', 'DESC')->paginate($limit);
    }

    public static function getSpanTitle($title){
        $title = str_replace("   "," ",$title);
        $title = explode("  ",$title);
        $j = 0; $res='';
        foreach($title as $t){
            ($j++ % 2) ? $res .= "<span>{$t}</span> " : $res .= $t." ";
        }
        return $res;
    }

    public static function getLink($post, $home=false, $cat=false){
        $post = self::find($post);
        if($home){
            $link = $post->pcategory()->first()->slug.'/'.$post->slug.'/'.$post->id;
        }else{
            $category = PCategory::find($cat);
            $link = $category->slug.'/'.$post->slug.'/'.$post->id;
        }
        return $link;
    }

    public function pcategory(){
        return $this->belongsToMany('App\PCategory');
    }

    public function tag(){
        return $this->belongsToMany(Tag::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
