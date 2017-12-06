<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use Session;

class Category extends Model
{
    use Translatable;

    public static $list_limit = 50;

    public $translatedAttributes = ['title', 'slug', 'desc'];

    protected $table = 'categories';

    protected $fillable = ['id', 'brand_id', 'order', 'parent', 'level', 'image', 'feature_image', 'collection', 'publish'];

    public static function save_cat_order($niz){
        $i=-1;
        foreach($niz as $n){
            $i++;
            if($i>0){
                if($n['parent_id'] == null){
                    self::save_order($n['item_id'], $i, false, $n['depth']);
                }else{
                    self::save_order($n['item_id'], $i, $n['parent_id'], $n['depth']);
                }
            }
        }
    }

    public static function save_order($id, $poz, $parent = false, $depth){
        if($parent){
            self::findOrFail($id)->update(array('order' => $poz, 'parent' => $parent, 'level' => $depth));
        }else{
            self::findOrFail($id)->update(array('order' => $poz, 'parent' => 0, 'level' => $depth));
        }
    }

    public static function getSortCategory($cat = false){
        if($cat){
            $category = self::where(array('publish' => 1, 'parent' => $cat))->orderby('order', 'ASC')->get();
        }else{
            $category = self::where(array('publish' => 1, 'parent' => 0))->orderby('order', 'ASC')->get();
        }
        $str="";
        if(isset($category)){
            $str .=  "<ol class='sortable'>";
            foreach($category as $c){
                $str .= "<li id='list_{$c->id}'><div>$c->title</div>";
                $str .= self::getSortCategory($c->id);
                $str .= "</li>";
            }
            $str .= "</ol>";
        }
        return $str;
    }

    public static function getMobileNav($topCat, $info){
        $settings = Setting::first();
        $str = '<ul>';
        if(app()->getLocale() == 'sr'){
            $str .= '<li style="display: flex; display: -webkit-flex;">
                        <a href="'. url('sr').'" style="flex: 1; color: #d9241b;">srb</a>
                        <a href="'. url('en').'" style="flex: 1;">eng</a>
                        <a href="'. url('ru').'" style="flex: 1;">рус</a>
                        <a style="flex: 1;"></a>
                    </li>';
            $str .= '<li><a href="'. url('/').'">Početna</a></li>';
        }elseif(app()->getLocale() == 'en'){
            $str .= '<li style="display: flex; display: -webkit-flex;">
                        <a href="'. url('sr').'" style="flex: 1;">srb</a>
                        <a href="'. url('en').'" style="flex: 1; color: #d9241b;">eng</a>
                        <a href="'. url('ru').'" style="flex: 1;">рус</a>
                        <a style="flex: 1;"></a>
                    </li>';
            $str .= '<li><a href="'. url('/').'">Home</a></li>';
        }else{
            $str .= '<li style="display: flex; display: -webkit-flex;">
                        <a href="'. url('sr').'" style="flex: 1;">srb</a>
                        <a href="'. url('en').'" style="flex: 1;">eng</a>
                        <a href="'. url('ru').'" style="flex: 1; color: #d9241b;">рус</a>
                        <a style="flex: 1;"></a>
                    </li>';
            $str .= '<li><a href="'. url('/').'">Главная</a></li>';
        }
        if(count($topCat)){
            foreach($topCat as $top){
                $cat = self::getChild($top->id);
                if(count($cat)) {
                    $str .= '<li class="icon icon-arrow-left">';
                    $str .= '<a href="#">'.$top->title.'</a>';
                    $str .= '<div class="mp-level">';
                    $str .= '<h2 class="icon icon-display">'.$top->title.'</h2>';
                    if(app()->getLocale() == 'sr'){
                        $str .= '<a class="mp-back" href="#">Nazad</a>';
                    }elseif(app()->getLocale() == 'en'){
                        $str .= '<a class="mp-back" href="#">Back</a>';
                    }else {
                        $str .= '<a class="mp-back" href="#">Назад</a>';
                    }

                    $str .= '<ul>';
                    foreach($cat as $c){
                        $list = self::where('parent', $c->id)->where('publish', 1)->orderby('order', 'ASC')->get();
                        if(count($list) > 0){
                            $str .= self::getShopMobileMenu($list, $c->title, false);
                        }else{
                            $str .= '<li><a href="'. url(self::getShopLink($c->id)) .'">'.$c->title.'</a></li>';
                        }
                    }
                    $str .= '</ul>';

                    $str .= '</div></li>';
                }else{
                    $str .= '<li><a href="'.url($top->slug).'">'.$top->title.'</a></li>';
                }
            }
        }
        $str .= '<li style="display: flex; display: -webkit-flex;">
                    <a href="' . $settings->facebook . '" style="flex: 1; text-align: center; padding-left: 0; padding-right: 0;" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <a href="' . $settings->pinterest . '" style="flex: 1; text-align: center; padding-left: 0; padding-right: 0;" target="_blank"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a>
                    <a href="' . $settings->instagram . '" style="flex: 1; text-align: center; padding-left: 0; padding-right: 0;" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                </li>';
        if(false) {
            $str .= '<li><a href="' . url('info/o-nama/5') . '">O nama</a></li>';
            $str .= '<li><a href="' . url('info/marketing/10') . '">MARKETING</a></li>';
            $str .= '<li><a href="' . url('info/uputstvo/11') . '">UPUTSTVO</a></li>';
            $str .= '<li><a href="' . url('info/kontakt/7') . '">Kontakt</a></li>';
            $str .= '<li><a href="' . url('info/uslovi-koriscenja') . '">Uslovi korišćenja</a></li>';
        }
        $str .= '</ul>';
        return $str;
    }

    /*public static function getChildCategories($cat){
        $res = '';
        $res .= $cat.',';
        $child = self::where('parent', $cat)->get();
        if(count($child) > 0){
            foreach($child as $ch){
                $res .= $ch->id.',';
                $child = self::where('parent', $ch->id)->get();
                if(count($child) > 0){
                    $res .= self::getChildCategories($ch->id);
                }
            }
        }
        return $res;
    }*/

    public static function getShopMobileMenu($list, $parent, $first=true){
        $str='';

        if(count($list)){
            if($first){
                $str .= '<ul>';
            }

            $str .= '<li class="icon icon-arrow-left">';
            $str .= '<a href="#">'.$parent.'</a>';
            $str .= '<div class="mp-level">';
            $str .= '<a class="mp-back" href="#">nazad</a>';
            $str .= '<ul>';

            foreach($list as $l){
                $list2 = self::where('parent', $l->id)->where('publish', 1)->orderby('order', 'ASC')->get();
                if(count($list2)){
                    $str .= self::getShopMobileMenu($list2, $l->title);
                }else{
                    $str .= '<li><a href="'. url(self::getShopLink($l->id)) .'">'.$l->title.'</a></li>';
                }
            }
            $str .= '</ul></div></li>';

            if($first){
                $str .= '</ul>';
            }
        }

        return $str;
    }

    public static function getChild($id){
        return self::where(array('publish' => 1, 'parent' => $id))->orderby('order', 'ASC')->get();
    }

    public static function getCategorySelect($locale, $except=false, $parent=false){
        return self::select('category_translations.title as title', 'categories.id as id')->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('category_translations.locale', $locale)->where('categories.publish', 1)
            ->where(function ($query) use ($except){
                if($except){
                    $query->where('categories.id', '<>', $except);
                }
            })->where(function ($query) use ($parent){
                if($parent){
                    $query->where('categories.parent', $parent);
                }
            })->pluck('category_translations.title', 'categories.id');
    }

    public static function getCategorySelectAdmin($locale, $except=false, $parent=false){
        return self::select('category_translations.title as title', 'categories.id as id')->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('category_translations.locale', $locale)->where('categories.publish', 1)
            ->where(function ($query) use ($except){
                if($except){
                    $query->where('categories.id', '<>', $except);
                }
            })->where(function ($query) use ($parent){
                if($parent){
                    $query->where('categories.parent', $parent);
                }
            })->pluck('category_translations.title', 'categories.id')->prepend('Sve kategorije', 0);
    }

    public static function getCategorySelectPrepend($locale, $except=false, $parent=false){
        return self::select('category_translations.title as title', 'categories.id as id')->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('category_translations.locale', $locale)->where('categories.publish', 1)
            ->where(function ($query) use ($except){
                if($except){
                    $query->where('categories.id', '<>', $except);
                }
            })->where(function ($query) use ($parent){
                if($parent){
                    $query->where('categories.parent', $parent);
                }
            })->pluck('category_translations.title', 'categories.id')->prepend('Bez nad kategorije', 0);
    }

    /*public static function getLocaleCategories($parent=false){
        if($parent){
            return PCategory::where('publish', 1)->where('parent', $parent)->where(function ($query){
                if(app()->getLocale() == 'ru'){
                    $query->where('id', '<>', 15);
                }
            })->translatedIn(app()->getLocale())->orderby('order', 'ASC')->get();
        }else{
            return PCategory::where('publish', 1)->where('parent', 0)->where('parent', $parent)->where(function ($query){
                if(app()->getLocale() == 'ru'){
                    $query->where('id', '<>', 15);
                }
            })->translatedIn(app()->getLocale())->orderby('order', 'ASC')->get();
        }
    }*/

    public static function getCategoryLink($category, $locale = 'sr'){
        $link='';
        if($category){
            $link = url('shop/'.self::getShopLink($category->id, $locale));
        }
        return $link;
    }

    public static function getLangLink($locale, $cat=false, $post=false){
        $link='';
        if($post){
            $link = Post::getPostLink($post, $locale);
        }elseif($cat){
            $link = url($locale.'/'.self::getShopLink($cat, $locale));
        }
        return $link;
    }

    public static function getShopLink($id, $locale=false){
        $str = '';
        if($locale){
            $cat = self::find($id);
            if(isset($cat)){
                if($cat->parent > 0){
                    $str = $cat->{'slug:'.$locale}.'/'.$str;
                    $str = self::getShopLink($cat->parent, $locale).$str;
                }else{
                    $str = $cat->{'slug:'.$locale}.'/'.$str;
                }
            }
        }else{
            $cat = self::find($id);
            if(isset($cat)){
                if($cat->parent > 0){
                    $str = $cat->slug.'/'.$str;
                    $str = self::getShopLink($cat->parent).$str;
                }else{
                    $str = $cat->slug.'/'.$str;
                }
            }
        }
        return $str;
    }

    public static function isTranslate($category, $locale){
        if(isset($category)){
            $row = PCategoryTranslation::where('category_id', $category->id)->where('locale', $locale)->first();
            if(isset($row)){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public static function getSortCategoryRadio($cat = false, $catids){
        if($cat){
            $category = Category::where(array('publish' => 1, 'parent' => $cat))->orderby('order', 'ASC')->get();
        }else{
            $category = Category::where(array('publish' => 1, 'parent' => 0))->orderby('order', 'ASC')->get();
        }
        $str="";
        if(isset($category)){
            $str .=  "<ol class='sortable'>";
            foreach($category as $c){
                $str .= "<li id='list_{$c->id}' style='position: relative'>";
                $str .= "<div class='udesno'>{$c->{'title:sr'}}</div>";
                if (in_array($c->id, $catids)) {
                    $str .= "<input type='radio' name='parent' value='{$c->id}' checked='checked' class='right-sort'";
                    if($c->level > 3){ $str .= "disabled='true'"; }
                    $str .= ".>";
                }else {
                    $str .= "<input type='radio' name='parent' value='{$c->id}' class='right-sort'";
                    if($c->level > 3){ $str .= "disabled='true'"; }
                    $str .= ".>";
                }
                $str .= Category::getSortCategoryRadio($c->id, $catids);
                $str .= "</li>";
            }
            $str .= "</ol>";
        }
        return $str;
    }

    public static function getSortCategoryCheckbox($cat = false, $catids){
        if($cat){
            $category = Category::where(array('publish' => 1, 'parent' => $cat))->orderby('order', 'ASC')->get();
        }else{
            $category = Category::where(array('publish' => 1, 'parent' => 0))->orderby('order', 'ASC')->get();
        }
        $str="";
        if(count($category) > 0){
            $str .=  "<ol class='sortable'>";
            foreach($category as $c){
                $str .= "<li id='list_{$c->id}' style='position: relative'>";
                $str .= "<div class='udesno'>{$c->{'title:sr'}}</div>";
                if (in_array($c->id, $catids)) {
                    $str .= "<input type='checkbox' name='kat[]' value='{$c->id}' checked='checked' class='right-sort'>";
                }else {
                    $str .= "<input type='checkbox' name='kat[]' value='{$c->id}' class='right-sort'>";
                }
                $str .= self::getSortCategoryCheckbox($c->id, $catids);
                $str .= "</li>";
            }
            $str .= "</ol>";
        }
        return $str;
    }

    public static function getSortCategorySelectAdmin(){
        $category = self::where(array('parent' => 0))->orderby('order', 'ASC')->get();
        $str="";
        if(isset($category)){
            $str .=  "<select name='category_id' class='sele' id='kategorija'>";
            $str .=  "<option value='0'>Sve kategorije</option>";
            foreach($category as $c){
                $separator = self::getSeparator($c->level);
                $str .=  "<option value='{$c->id}'";
                if(Session::get('cat') == $c->id){ $str .= "selected>"; }else{ $str .= ">"; }
                $str .= $separator." {$c->order}. {$c->{'title:sr'}}";
                $str .= "</option>";
                $str .= self::getSortCategorySelectParentAdmin($c->id);
            }
            $str .= "</select>";
        }
        return $str;
    }

    public static function getSortCategorySelectPosts(){
        $category = self::where(array('publish' => 1, 'parent' => 0))->where('level', '<', 4)->orderby('order', 'ASC')->get();
        $str="";
        if(isset($category)){
            $str .=  "<select name='category_id' class='form-control' id='kategorija'>";
            $str .=  "<option value='0'>Sve kategorije</option>";
            foreach($category as $c){
                $separator = self::getSeparator($c->level);
                $str .=  "<option value='{$c->id}'";
                if(Session::get('post_cat') == $c->id){ $str .= "selected>"; }else{ $str .= ">"; }
                $str .= $separator." {$c->order}. {$c->{'title:sr'}}";
                $str .= "</option>";
                $str .= self::getSortCategorySelectParent($c->id);
            }
            $str .= "</select>";
        }
        return $str;
    }

    public static function getSortCategorySelectParent($cat = false){
        $str="";
        if($cat){
            $category = self::where(array('publish' => 1, 'parent' => $cat))->where('level', '<', 4)->orderby('order', 'ASC')->get();
            if(isset($category)){
                foreach($category as $c){
                    $separator = self::getSeparator($c->level);
                    $str .=  "<option value='{$c->id}'";
                    if(Session::get('cat') == $c->id){ $str .= "selected>"; }else{ $str .= ">"; }
                    $str .= $separator." {$c->order}. {$c->{'title:sr'}}";
                    $str .= "</option>";
                    $str .= self::getSortCategorySelectParent($c->id);
                }
            }
        }
        return $str;
    }

    public static function getSortCategorySelectParentAdmin($cat = false){
        $str="";
        if($cat){
            $category = self::where(array('parent' => $cat))->where('level', '<', 4)->orderby('order', 'ASC')->get();
            if(isset($category)){
                foreach($category as $c){
                    $separator = self::getSeparator($c->level);
                    $str .=  "<option value='{$c->id}'";
                    if(Session::get('cat') == $c->id){ $str .= "selected>"; }else{ $str .= ">"; }
                    $str .= $separator." {$c->order}. {$c->{'title:sr'}}";
                    $str .= "</option>";
                    $str .= self::getSortCategorySelectParentAdmin($c->id);
                }
            }
        }
        return $str;
    }

    public static function getSeparator($id){
        $str = '';
        if($id == 2){
            $str .= '-';
        }elseif($id == 3){
            $str .= '--';
        }elseif($id == 4){
            $str .= '---';
        }
        elseif($id == 5){
            $str .= '----';
        }
        return $str;
    }

    public static function getLastCategoryObject($product){
        if(count($product->category)>0){
            $level = 0;
            $cat = null;
            foreach ($product->category as $category){
                if($category->level > $level){
                    $level = $category->level;
                    $cat = $category;
                }
            }
            return $cat;
        }
        return null;
    }

    public static function add100($cats){
        if(count($cats)>0){
            $res = [];
            foreach($cats as $cat){
                $res[] = $cat + 100;
            }
            return $res;
        }
        return [];
    }

    public static function getCollections($id=false){
        if($id){
            $category = self::find($id);
            return self::where('parent', $category->id)->where('publish', 1)->orderby('order', 'ASC')->get();
        }else{
            return self::where('level', 1)->where('publish', 1)->orderby('order', 'ASC')->get();
        }
    }

    public function post(){
        return $this->hasMany(Post::class);
    }

    public function product(){
        return $this->belongsToMany(Product::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function property(){
        return $this->belongsToMany(Property::class);
    }

    public function translate(){
        return $this->hasMany(CategoryTranslation::class);
    }

}
