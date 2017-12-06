<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuLink extends Model
{
    public static $list_limit = 50;

    protected $table = 'menu_links';

    protected $fillable = ['id', 'menu_id', 'cat_id', 'title', 'link' , 'desc', 'sufix', 'type', 'order', 'parent', 'level' , 'image', 'locale', 'publish'];

    public static function save_cat_order($menuLinksIds, $niz){
        $i=-1;
        foreach($niz as $n){
            $i++;
            if($i>0){
                $link = self::find($menuLinksIds[$i-1]);
                //$link->id = $n['item_id'];
                $link->update();
                if($n['parent_id'] == null){
                    self::save_order($link->id, $n['item_id'], $i, false, $n['depth']);
                }else{
                    self::save_order($link->id, $n['item_id'], $i, $n['parent_id'], $n['depth']);
                }
            }
        }
    }

    public static function save_order($menu_id, $id, $poz, $parent = false, $depth){
        if($parent){
            self::findOrFail($menu_id)->update(array('cat_id' => $id, 'order' => $poz, 'parent' => $parent, 'level' => $depth));
        }else{
            self::findOrFail($menu_id)->update(array('cat_id' => $id, 'order' => $poz, 'parent' => 0, 'level' => $depth));
        }
    }

    public static function deleteLinks($menu){
        if(count($menu->menuLinks)>0){
            foreach ($menu->menuLinks as $link){
                if($link->custom){
                    $link->parent = 0;
                    $link->level = 1;
                    $link->update();
                }else{
                    $link->delete();
                }
            }
        }
    }

    public static function getSomeLink($id, $locale=false, $menuLink=false){
        $str = '';
        if($menuLink){
            if($menuLink->type == 1){
                $cat = Category::find($menuLink->cat_id);
            }else{
                $cat = PCategory::find($menuLink->cat_id);
            }
        }else{
            $menuLink = self::find($id);
            if($menuLink->type == 1){
                $cat = Category::find($menuLink->cat_id);
            }else{
                $cat = PCategory::find($menuLink->cat_id);
            }
        }
        if(isset($cat)){
            if($menuLink->parent > 0){
                $menuLink2 = self::where('cat_id', $menuLink->parent)->where('locale', $locale)
                    ->where('menu_id', $menuLink->menu_id)->first();
                $str = $cat->{'slug:'.$locale}.'/'.$str;
                $str = self::getSomeLink($menuLink->parent, $locale, $menuLink2).$str;
            }else{
                $str = $cat->{'slug:'.$locale}.'/'.$str;
            }
        }
        return $str;
    }

    public static function makeShopLink($menu, $id, $locale='sr'){
        $link = substr(self::getSomeLink($id,$locale, false), 0, -1);
        $link = 'shop/' . $link;
        if($menu->prefix != null){
            $link = $menu->prefix . '/' . $link;
        }
        if($menu->sufix != null){
            $link = $link . '/' . $menu->sufix;
        }
        return $link;
    }

    public static function makeBlogLink($menu, $id, $locale='sr'){
        $link = substr(self::getSomeLink($id, $locale, false), 0, -1);
        if($menu->prefix != null){
            $link = $menu->prefix . '/' . $link;
        }
        if($menu->sufix != null){
            $link = $link . '/' . $menu->sufix;
        }
        return $link;
    }

    public static function setLinks($menu, $links2){
        $languages = Language::where('publish', 1)->orderBy('order', 'ASC')->get();
        if(count($languages)>0){
            foreach ($languages as $language){
                $links = self::where('menu_id', $menu->id)->where('locale', $language->locale)->get();
                if(count($links)>0){
                    foreach ($links as $l){
                        if(count($links2)>0){
                            foreach ($links2 as $ll2){
                                if($ll2->image != null && $l->title == $ll2->title && $l->locale == $ll2->locale && $menu->id == $ll2->menu_id){
                                    $l->image = $ll2->image;
                                }
                            }
                        }
                        if($l->type == 1){
                            $l->link = self::makeShopLink($menu, $l->id, $language->locale);
                        }elseif($l->type == 2){
                            $l->link = self::makeBlogLink($menu, $l->id, $language->locale);
                        }else{
                            $ll = self::getLink($links2, $l->cat_id);
                            isset($ll)? $link = $ll : $link = null;
                            $l->link = $link;
                        }
                        $l->update();
                    }
                }
            }
        }
    }

    public static function renderMobileMenuHtml($menu){
        $str = '<ul>';

        $str .= '<li><a href="'. url('/').'">Početna</a></li>';
        $links = $menu->menuLinks()->where('parent', 0)->where('publish', 1)->orderBy('order', 'ASC')->get();
        if(count($links)>0){
            foreach($links as $link){
                $cat = $menu->menuLinks()->where('parent', $link->id)->where('publish', 1)->orderBy('order', 'ASC')->get();
                if(count($cat)) {
                    $str .= '<li class="icon icon-arrow-left">';
                    $str .= '<a href="#">'.$link->title.'</a>';
                    $str .= '<div class="mp-level">';
                    $str .= '<h2 class="icon icon-display">'.$link->title.'</h2>';
                    $str .= '<a class="mp-back" href="#">Nazad</a>';

                    $str .= '<ul>';
                    foreach($cat as $c){
                        $list = self::where('parent', $c->id)->where('publish', 1)->orderby('order', 'ASC')->get();
                        if(count($list) > 0){
                            $str .= Category::getShopMobileMenu($list, $c->title, false);
                        }else{
                            $str .= '<li><a href="'. url($c->link) .'">'.$c->title.'</a></li>';
                        }
                    }
                    $str .= '</ul>';

                    $str .= '</div></li>';
                }else{
                    $str .= '<li><a href="'.url($link->link).'">'.$link->title.'</a></li>';
                }
            }
        }
        $str .= '</ul>';
        return $str;
    }

    public static function getTitle($links, $cat_id){
        if(count($links)>0){
            foreach ($links as $link){
                if($link->cat_id == $cat_id){
                    return $link->title;
                }
            }
        }
        return null;
    }

    public static function getLink($links, $cat_id){
        if(count($links)>0){
            foreach ($links as $link){
                if($link->cat_id == $cat_id){
                    return $link->link;
                }
            }
        }
        return null;
    }

    public static function setAttributes($attributes){
        $res = [];
        if(count($attributes)>0){
            foreach ($attributes as $attribute){
                if($attribute == '[]'){
                    $res[] = [];
                }else{
                    $att = substr($attribute, 1, -1);
                    $niz = explode(',', $att);
                    $res[] = $niz;
                }
            }
        }
        return $res;
    }

    public static function addSufix($link_id=false){
        if($link_id){
            $link = self::find($link_id);
            if(count($link->attribute)>0){
                $res = '?';
                $br=0;
                foreach ($link->attribute as $attribute){
                    $res .= 'filters[]='.$attribute->id;
                    $br++;
                    if($br < count($link->attribute)){
                        $res .= '&';
                    }
                }
                return $res;
            }else{
                return null;
            }
        }else{
            $links = self::all();
            if(count($links)>0){
                foreach ($links as $link){
                    $slug = self::addSufix($link->id);
                    $link->sufix = $slug;
                    $link->update();
                }
            }
        }
    }

    public static function linkRender($link){
        if(substr($link, 0, 4) == 'http'){
            return $link;
        }else{
            return url($link);
        }
    }

    /**** MOBILE NAV *****/

    public static function getMobileNav($topMenu){
        $str = '<ul>';
        if(count($topMenu)){
            foreach($topMenu as $top){
                $links = self::getChild($top->cat_id);
                if(count($links)) {
                    $str .= '<li class="icon icon-arrow-left">';
                    $str .= '<a href="#">'.$top->title.'</a>';
                    $str .= '<div class="mp-level">';
                    $str .= '<h2 class="icon icon-display">'.$top->title.'</h2>';
                    $str .= '<a class="mp-back" href="#">Nazad</a>';

                    $str .= '<ul>';
                    foreach($links as $link){
                        $list = self::where('parent', $link->cat_id)->where('publish', 1)->orderby('order', 'ASC')->get();
                        if(count($list) > 0){
                            $str .= self::getShopMobileMenu($list, $link->title, false);
                        }else{
                            $str .= '<li><a href="'. url($link->link . $link->sufix) .'">'.$link->title.'</a></li>';
                        }
                    }
                    $str .= '</ul>';

                    $str .= '</div></li>';
                }else{
                    $str .= '<li><a href="'.url($top->link. $top->sufix).'">'.$top->title.'</a></li>';
                }
            }
        }
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

    public static function getChild($id){
        return self::where(array('publish' => 1, 'parent' => $id))->orderby('order', 'ASC')->get();
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
                    $str .= '<li><a href="'. url($l->link) .'">'.$l->title.'</a></li>';
                }
            }
            $str .= '</ul></div></li>';

            if($first){
                $str .= '</ul>';
            }
        }

        return $str;
    }

    /**** END MOBILE NAV *****/

    public static function getTopParentBylink($link=false){
        if($link){
            $menuLink = self::where('publish', 1)->where('link', $link)->first();
            if(isset($menuLink)){
                return $menuLink;
            }
        }
        return self::where('publish', 1)->where('parent', 0)->orderby('order', 'ASC')->first();
    }

    public function menu(){
        return $this->belongsTo(Menu::class);
    }

    public function attribute(){
        return $this->belongsToMany(Attribute::class);
    }
}
