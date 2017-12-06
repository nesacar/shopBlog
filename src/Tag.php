<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Dimsav\Translatable\Translatable;

class Tag extends Model
{
    use Translatable;

    public static $list_limit = 50;

    public $translatedAttributes = ['title', 'slug'];

    protected $table = 'tags';

    protected $fillable = ['title', 'slug'];

    public static function addTags(array $tags, $locale){
        for($i=0;$i<count($tags);$i++){
            $tag = self::select('tags.id as id')->join('tag_translations', 'tags.id', '=', 'tag_translations.tag_id')
                ->where('tag_translations.locale', $locale)->where('tags.id', $tags[$i])->first();
            if(!isset($tag)){
                $ta = new Tag;
                $ta->title = $tags[$i];
                $ta->slug = Str::slug($tags[$i]);
                $ta->save();

                $tags = array_diff($tags, [$tags[$i]]);
                $tags[$i] = (string)$ta->id;
                //$ta->translate($locale)->title = $tags[$i];
                //$ta->translate($locale)->slug = Str::slug($tags[$i]);
            }else{
                $tags[$i] = (string)$tag->id;
            }
        }
        return $tags;
    }

    public static function getTagSelect($locale){
        return self::select('tag_translations.title as title', 'tags.id as id')->join('tag_translations', 'tags.id', '=', 'tag_translations.tag_id')
            ->where('tag_translations.locale', $locale)->pluck('title', 'id');
    }

    public function post(){
        return $this->belongsToMany(Post::class);
    }
}
