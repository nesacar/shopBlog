<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;
use Illuminate\Support\Str;
use Dimsav\Translatable\Translatable;

class PCategory extends Model {

    use Translatable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'p_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $translatedAttributes = ['title', 'slug', 'desc', 'body'];

    protected $fillable = ['brand_id', 'parent', 'level', 'order', 'customProperty', 'show_products', 'image', 'publish', 'w1', 'w2', 'w3', 'w4', 'w5', 'w6', 'cat1', 'cat2'];

    public static function save_order($id, $poz, $parent = false, $depth){
        if($parent){
            PCategory::findOrFail($id)->update(array('order' => $poz, 'parent' => $parent, 'level' => $depth));
        }else{
            PCategory::findOrFail($id)->update(array('order' => $poz, 'parent' => 0, 'level' => $depth));
        }
    }

    public static function save_cat_order($niz){
        $i=-1;
        foreach($niz as $n){
            $i++;
            if($i>0){
                if($n['parent_id'] == null){
                    PCategory::save_order($n['item_id'], $i, false, $n['depth']);
                }else{
                    PCategory::save_order($n['item_id'], $i, $n['parent_id'], $n['depth']);
                }
            }
        }
    }

    /*public static function get_cat_order_top($cats){
        $array = array();
        foreach($cats as $page){
            $array[$page->id] = Category::where('parent', $page->id)->where('publish', 1)->get();
        }
        return $array;
    }*/

    /*public static function get_cat_order_sub(){
        $cats = Category::where('publish', 1)->orderby('order', 'ASC')->get();
        $array = array();
        foreach($cats as $page){
            if($page->parent > 0){
                $array[$page->parent] = $page;
            }
        }
        return $array;
    }*/

    public static function getTop(){
        return PCategory::where('publish', 1)->where('top', 1)->orderby('order', 'ASC')->get();
    }

    public static function getSub($cat){
        return PCategory::where('publish', 1)->where('parent', $cat)->orderby('order', 'ASC')->get();
    }

    public static function getSortCategory($cat = false){
        if($cat){
            $category = PCategory::where(array('publish' => 1, 'parent' => $cat))->orderby('order', 'ASC')->get();
        }else{
            $category = PCategory::where(array('publish' => 1, 'parent' => 0))->orderby('order', 'ASC')->get();
        }
        $str="";
        if(isset($category)){
            $str .=  "<ol class='sortable'>";
            foreach($category as $c){
                $title =
                $str .= "<li id='list_{$c->id}'><div>{$c->{'title:sr'}}</div>";
                $str .= PCategory::getSortCategory($c->id);
                $str .= "</li>";
            }
            $str .= "</ol>";
        }
        return $str;
    }

    public static function getSortCategoryCheckbox($cat = false, $catids){
        if($cat){
            $category = PCategory::where(array('publish' => 1, 'parent' => $cat))->orderby('order', 'ASC')->get();
        }else{
            $category = PCategory::where(array('publish' => 1, 'parent' => 0))->orderby('order', 'ASC')->get();
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
                $str .= PCategory::getSortCategoryCheckbox($c->id, $catids);
                $str .= "</li>";
            }
            $str .= "</ol>";
        }
        return $str;
    }

    public static function getSortCategoryRadio($cat = false, $catids){
        if($cat){
            $category = PCategory::where(array('publish' => 1, 'parent' => $cat))->orderby('order', 'ASC')->get();
        }else{
            $category = PCategory::where(array('publish' => 1, 'parent' => 0))->orderby('order', 'ASC')->get();
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
                $str .= PCategory::getSortCategoryRadio($c->id, $catids);
                $str .= "</li>";
            }
            $str .= "</ol>";
        }
        return $str;
    }

    /*public static function getSortCategoryProductGroupRadio($cat = false, $catids, $product_group_id){
        if($cat){
            $category = PCategory::where(array('publish' => 1, 'parent' => $cat))->orderby('order', 'ASC')->get();
        }else{
            $category = PCategory::where(array('publish' => 1, 'parent' => 0))->orderby('order', 'ASC')->get();
        }
        $str="";
        if(isset($category)){
            $str .=  "<ol class='sortable'>";
            foreach($category as $c){
                $str .= "<li id='list_{$c->id}' style='position: relative'>";
                $str .= "<div class='udesno'>{$c->{'title:sr'}}</div>";
                if ($product_group_id == $c->id) {
                    $str .= "<input type='radio' name='parent' value='{$c->id}' checked='checked' class='right-sort'";
                    if($c->level > 3){ $str .= "disabled='true'"; }
                    $str .= ".>";
                }else {
                    $str .= "<input type='radio' name='parent' value='{$c->id}' class='right-sort'";
                    if($c->level > 3){ $str .= "disabled='true'"; }
                    $str .= ".>";
                }
                $str .= PCategory::getSortCategoryProductGroupRadio($c->id, $catids, $product_group_id);
                $str .= "</li>";
            }
            $str .= "</ol>";
        }
        return $str;
    }*/

    /*public static function getSortCategorySelect(){
        $category = PCategory::where(array('publish' => 1, 'parent' => 0))->orderby('order', 'ASC')->get();
        $str="";
        if(isset($category)){
            $str .=  "<select name='category_id' class='form-control' id='kategorija'>";
            $str .=  "<option value='0'>Sve kategorije</option>";
            foreach($category as $c){
                $separator = PCategory::getSeparator($c->level);
                $str .=  "<option value='{$c->id}'";
                if(Session::get('cat') == $c->id){ $str .= "selected>"; }else{ $str .= ">"; }
                $str .= $separator." {$c->order}. {$c->{'title:sr'}}";
                $str .= "</option>";
                $str .= PCategory::getSortCategorySelectParent($c->id);
            }
            $str .= "</select>";
        }
        return $str;
    }*/

    public static function getSortCategorySelectAdmin(){
        $category = PCategory::where(array('parent' => 0))->orderby('order', 'ASC')->get();
        $str="";
        if(isset($category)){
            $str .=  "<select name='category_id' class='sele' id='kategorija'>";
            $str .=  "<option value='0'>Sve kategorije</option>";
            foreach($category as $c){
                $separator = PCategory::getSeparator($c->level);
                $str .=  "<option value='{$c->id}'";
                if(Session::get('cat') == $c->id){ $str .= "selected>"; }else{ $str .= ">"; }
                $str .= $separator." {$c->order}. {$c->{'title:sr'}}";
                $str .= "</option>";
                $str .= PCategory::getSortCategorySelectParentAdmin($c->id);
            }
            $str .= "</select>";
        }
        return $str;
    }

    public static function getSortCategorySelectPosts(){
        $category = PCategory::where(array('publish' => 1, 'parent' => 0))->where('level', '<', 4)->orderby('order', 'ASC')->get();
        $str="";
        if(isset($category)){
            $str .=  "<select name='category_id' class='form-control' id='kategorija'>";
            $str .=  "<option value='0'>Sve kategorije</option>";
            foreach($category as $c){
                $separator = PCategory::getSeparator($c->level);
                $str .=  "<option value='{$c->id}'";
                if(Session::get('post_cat') == $c->id){ $str .= "selected>"; }else{ $str .= ">"; }
                $str .= $separator." {$c->order}. {$c->{'title:sr'}}";
                $str .= "</option>";
                $str .= PCategory::getSortCategorySelectParent($c->id);
            }
            $str .= "</select>";
        }
        return $str;
    }

    /*public static function getSortCategorySelectParent($cat = false){
        $str="";
        if($cat){
            $category = PCategory::where(array('publish' => 1, 'parent' => $cat))->where('level', '<', 4)->orderby('order', 'ASC')->get();
            if(isset($category)){
                foreach($category as $c){
                    $separator = PCategory::getSeparator($c->level);
                    $str .=  "<option value='{$c->id}'";
                    if(Session::get('cat') == $c->id){ $str .= "selected>"; }else{ $str .= ">"; }
                    $str .= $separator." {$c->order}. {$c->{'title:sr'}}";
                    $str .= "</option>";
                    $str .= PCategory::getSortCategorySelectParent($c->id);
                }
            }
        }
        return $str;
    }

    public static function getSortCategorySelectParentAdmin($cat = false){
        $str="";
        if($cat){
            $category = PCategory::where(array('parent' => $cat))->where('level', '<', 4)->orderby('order', 'ASC')->get();
            if(isset($category)){
                foreach($category as $c){
                    $separator = PCategory::getSeparator($c->level);
                    $str .=  "<option value='{$c->id}'";
                    if(Session::get('cat') == $c->id){ $str .= "selected>"; }else{ $str .= ">"; }
                    $str .= $separator." {$c->order}. {$c->{'title:sr'}}";
                    $str .= "</option>";
                    $str .= PCategory::getSortCategorySelectParentAdmin($c->id);
                }
            }
        }
        return $str;
    }*/

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


    public static function getShopLink($id, $locale=false){
        $str = '';
        if($locale){
            $cat = PCategory::find($id);
            if(isset($cat)){
                if($cat->parent > 0){
                    $str = $cat->{'slug:'.$locale}.'/'.$str;
                    $str = self::getShopLink($cat->parent, $locale).$str;
                }else{
                    $str = $cat->{'slug:'.$locale}.'/'.$str;
                }
            }
        }else{
            $cat = PCategory::find($id);
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

    public static function getMobileNav($topCat, $info){
        $str = '<ul>';
        $str .= '<li><a href="'. url('/').'">'.trans('language.Homepage').'</a></li>';
        if(count($topCat)){
            foreach($topCat as $top){
                $cat = PCategory::getChild($top->id);
                if(count($cat)) {
                    $str .= '<li class="icon icon-arrow-left">';
                    $str .= '<a href="#">'.$top->title.'</a>';
                    $str .= '<div class="mp-level">';
                    $str .= '<h2 class="icon icon-display">'.$top->title.'</h2>';
                    $str .= '<a class="mp-back" href="#">'.trans('language.Back').'</a>';

                    $str .= '<ul>';
                    foreach($cat as $c){
                        $list = PCategory::where('parent', $c->id)->where('publish', 1)->orderby('order', 'ASC')->get();
                        if(count($list) > 0){
                            $str .= PCategory::getShopMobileMenu($list, $c->title, false);
                        }else{
                            $str .= '<li><a href="'. url('shop/'.self::getShopLink($c->id)) .'">'.$c->title.'</a></li>';
                        }
                    }
                    $str .= '</ul>';

                    $str .= '</div></li>';
                }else{
                    $str .= '<li><a href="#">'.$top->title.'</a></li>';
                }

            }

        }
        $str .= '</ul>';
        return $str;
    }

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
                    $str .= '<li><a href="'. url('shop/'.self::getShopLink($l->id)) .'">'.$l->title.'</a></li>';
                }
            }
            $str .= '</ul></div></li>';

            if($first){
                $str .= '</ul>';
            }
        }

        return $str;
    }

    public static function getCategoryBrend($cat=false){
        if($cat){
            return self::where('id', '<>', $cat)->where('level', 2)->where('publish', 1)->orderby('order', 'ASC')->get();
        }else{
            return self::where('level', 2)->where('publish', 1)->orderby('order', 'ASC')->get();
        }
    }

    /*public static function getFirstShopCategory(){
        $cat = self::where('publish', 1)->where('parent', 0)->orderby('order', 'ASC')->first();
        return url('shop/'.$cat->slug);
    }*/

    public static function deleteCategory($id, $delete=false){
        $category = self::find($id);
        $children = self::where('publish', 1)->where('parent', $id)->get();
        if(count($children)){
            foreach($children as $ch){
                /*self::deleteCategory($ch->id);*/
                $ch->parent = 0;
                $ch->level = 1;
                $ch->publish = 0;
                $ch->update();
            }
        }
        if($delete){
            $category->delete();
        }
    }

    public static function getMainCategory($id){
        $category = self::find($id);
        $parent  = self::where('publish', 1)->where('id', $category->parent)->first();
        if(isset($parent)){
            $category = self::getMainCategory($parent->id);
        }
        return $category;
    }

    /*public static function isParentContainsThisChild($parent, $child){
        $res=false;
        $ch1 = self::where('publish', 1)->where('parent', $parent)->where('id', $child)->first();
        if(isset($ch1)){
            $res=true;
        }else{
            $children = self::where('publish', 1)->where('parent', $parent)->get();
            if(count($children)){
                foreach($children as $ch){
                    $res = self::isParentContainsThisChild($ch->id, $child);
                    if($res) break;
                }
            }
        }
        return $res;
    }*/


    /*public static function getShopCategories($id, $main_category=false, $cat=false, $first=false){
        $str='';

        if(!$main_category){
            $main_category = self::getMainCategory($id);
            $children = self::where('publish', 1)->where('parent', $main_category->id)->orderby('order', 'ASC')->get();
        }else{
            $children = self::where('publish', 1)->where('parent', $cat->id)->orderby('order', 'ASC')->get();
        }

        if(isset($children)){

            if($first){
                $str.='<ul class="main-nav">';
            }else{
                $str.='<ul class="sek open">';
            }

            foreach($children as $ch){
                $str.='<li';
                $link = url('shop/'.self::getShopLink($ch->id));
                if($first){
                    $str.=' class="topic"><a href="'.$link.'"';
                }else{
                    $str.='><a href="'.$link.'"';
                }
                if($ch->id == $id){
                    $str.=' class="active">'.$ch->title.'</a>';
                    //ovde se prikazuju
                    $str.=self::getCategoryChildren($ch->id);
                }else{
                    $str.='>'.$ch->title.'</a>';
                }
                if(self::isParentContainsThisChild($ch->id, $id)){
                    $str.= $s = self::getShopCategories($id, true, $ch);
                }
                if($first){
                    $str.='<span class="num" style="top: 9px">'.$ch->product()->where('amount', '>', 0)->where('publish', 1)->count().'</span></li>';
                }else{
                    $str.='<span class="num">'.$ch->product()->where('amount', '>', 0)->where('publish', 1)->count().'</span></li>';
                }
            }
            $str.='</ul>';
        }

        return $str;
    }*/

    /*public static function getCategoryChildren($id){
        $str='';
        $children = self::where('publish', 1)->where('parent', $id)->orderby('order', 'ASC')->get();
        if(count($children)){
            $str.='<ul class="sek open">';
            foreach($children as $ch){
                $link = url('shop/'.self::getShopLink($ch->id));
                $str.='<li><a href="'.$link.'">'.$ch->title.'</a><span class="num">'.$ch->product()->where('amount', '>', 0)->where('publish', 1)->count().'</span></li>';
            }
            $str.='</ul>';
        }

        return $str;
    }*/

    /*public static function showProductByCategory($cat, $show=false){
        $category = PCategory::find($cat);
        $cats = self::getChildCategories($cat);
        $cats = substr($cats, 0, -1);
        $res = explode(",",$cats);
        $unique = array_unique($res);
        if($show){
            //prikazi
            if(count($unique) > 0){
                foreach($unique as $un){
                    $cat = PCategory::find($un);
                    $products = $cat->product;
                    if(count($products) > 0){
                        $cat->show_products = 1;
                        $cat->update();
                        foreach($products as $pr){
                            $pr->publish = 1;
                            $pr->update();
                        }
                    }
                }
            }
        }else{
            //sakrij
            if(count($unique) > 0){
                foreach($unique as $un){
                    $cat = PCategory::find($un);
                    $products = $cat->product;
                    if(count($products) > 0){
                        $cat->show_products = 0;
                        $cat->update();
                        foreach($products as $pr){
                            $pr->publish = 0;
                            $pr->update();
                        }
                    }
                }
            }
        }
        return true;
    }*/

    public static function getChildCategories($cat){
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
    }

    public static function getTopCategory($id){
        $category = self::find($id);
        if($category->level == 1){
            return $category;
        }else{
            $parent = self::where('id', $category->parent)->first();
            if(count($parent)){
                return self::getTopCategory($parent->id);
            }else{
                return $category;
            }
        }
    }

    public static function getLangCategory($slug, $id=false, $locale = 'sr'){
        if($id){
            $cat_id = PCategoryTranslation::where('slug', $slug)->where('locale', $locale)->pluck('p_category_id')->first();
            isset($cat_id)? $category = PCategory::where('id', $cat_id)->where('publish', 1)->where('parent', $id)->first() : $category = null;
        }else{
            $cat_id = PCategoryTranslation::where('slug', $slug)->where('locale', $locale)->pluck('p_category_id')->first();
            isset($cat_id)? $category = PCategory::where('id', $cat_id)->where('publish', 1)->first() : $category = null;
        }
        return $category;
    }

    public static function getChild($id){
        return self::where(array('publish' => 1, 'parent' => $id))->orderby('order', 'ASC')->get();
    }

    public static function getLangLink($locale, $category=false){
        if(isset($category)){
            if($category->parent == 0){
                return url($category->translate($locale)->slug);
            }else{
                $cat = PCategory::find($category->parent);
                return url($cat->translate($locale)->slug . '/' .$category->translate($locale)->slug);
            }
        }
        return '';
    }

    public static function replaceLocaleUrl($locale, $url){
        $array = explode("/",$url);
        return str_replace($array[3], $locale, $url);
    }

    public static function getTopParent($cat=false){
        if($cat){
            $category =  self::find($cat);
            if(isset($category)){
                if($category->parent != 0){
                    $category2 = self::where('publish', 1)->where('id', $category->parent)->first();
                    if(isset($category2)){
                        $res = self::getTopParent($category2->id);
                    }else{
                        $res = $category;
                    }
                }else{
                    $res = $category;
                }
            }else{
                $res = self::where('publish', 1)->where('parent', 0)->orderby('order', 'ASC')->first();
            }
        }else{
            $res = self::where('publish', 1)->where('parent', 0)->orderby('order', 'ASC')->first();
        }
        return $res;
    }

    public function post(){
        return $this->belongsToMany('App\Post');
    }

}
