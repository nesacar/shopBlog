<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $table = 'themes';

    protected $fillable = ['title', 'image', 'version', 'author', 'author_address', 'author_email', 'developer', 'active', 'publish'];

    public static function activateTheme($id){
        $themes = self::all();
        if(count($themes) > 0){
            foreach($themes as $t){
                $t->active = 0;
                $t->update();
            }
        }
        $theme = self::find($id);
        $theme->active = 1;
        $theme->update();
    }

    public static function deactivateTheme($id){
        $themes = self::all();
        if(count($themes) > 0){
            foreach($themes as $t){
                $t->active = 0;
                $t->update();
            }
        }
        $theme = self::first();
        $theme->active = 1;
        $theme->update();
    }
}
