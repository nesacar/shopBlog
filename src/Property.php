<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;

class Property extends Model {

    use Translatable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'properties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $translatedAttributes = ['title'];

    protected $fillable = ['id', 'order', 'spacial', 'price', 'publish'];

    public static function save_oso_order($niz){
        $i=-1;
        foreach($niz as $n){
            $i++;
            if($i>0){
                if($n['parent_id'] == null){
                    Property::save_order($n['item_id'], $i, false, $n['depth']);
                }else{
                    Property::save_order($n['item_id'], $i, $n['parent_id'], $n['depth']);
                }
            }
        }
    }

    public static function save_order($id, $poz, $parent = false, $depth){
        if($parent){
            Property::findOrFail($id)->update(array('order' => $poz, 'parent' => $parent, 'level' => $depth));
        }else{
            Property::findOrFail($id)->update(array('order' => $poz, 'parent' => 0, 'level' => $depth));
        }
    }

    public static function getSortOsobina($oso = false){
        if(!$oso){
            $osobina = Property::where(array('publish' => 1))->orderby('order', 'ASC')->get();
        }
        $str="";
        if(isset($osobina)){
            $str .=  "<ol class='sortable'>";
            foreach($osobina as $o){
                $str .= "<li id='list_{$o->id}'><div>$o->title</div>";
                $str .= Property::getSortOsobina($o->id);
                $str .= "</li>";
            }
            $str .= "</ol>";
        }
        return $str;
    }

    public static function countPropertyFilter($filters){
        if(count($filters)>0){
            return self::select('property_translations.title')
                ->join('property_translations', 'properties.id', '=', 'property_translations.property_id')
                ->join('attributes', 'properties.id', '=', 'attributes.property_id')
                ->whereIn('attributes.id', $filters)->groupby('properties.id')->get()->count();
        }
        return 0;
    }

    public static function sredi($filters){
        $niz = [];
        if(count($filters)>0){
            foreach ($filters as $filter){
                $niz[] = self::select('properties.id as property_id', 'property_translations.title as property_title', 'attributes.id as attribute_id', 'attribute_translations.title as attribute_title')
                    ->join('property_translations', 'properties.id', '=', 'property_translations.property_id')
                    ->join('attributes', 'properties.id', '=', 'attributes.property_id')
                    ->join('attribute_translations', 'attributes.id', '=', 'attribute_translations.attribute_id')
                    ->where('attributes.id', $filter)->where('properties.publish', 1)->groupBy('attributes.id')->get();
            }
        }

        $niz2 = [];
        if(count($niz)>0){
            foreach ($niz as $n){
                $niz2[$n[0]['property_id']][] = $n[0]['attribute_id'];
            }
        }
        return $niz2;
    }

    public static function isGroup($product_ids, $properties){
        $res = [];
        if(count($product_ids)>0){
            foreach ($product_ids as $id){
                $br=0;
                foreach ($properties as $property){
                    $j = false;
                    foreach ($property as $item){
                        if(\DB::table('attribute_product')->where('attribute_product.product_id', $id)->where('attribute_product.attribute_id', $item)->count()>0){
                            $j = true;
                        }
                    }
                    if($j){ $br++; }
                }
                if($br >= count($properties)){
                    $res[] = $id;
                }
            }
        }
        return $res;
    }

    public static function getPropertiesAndAttributesByCategory($category_id){
        $properties = Category::find($category_id)->property()->where('publish', 1)->orderBy('order', 'ASC')->get();
        if(!empty($properties)){
            $properties->map(function ($property) use ($category_id){
                $property['attributes'] = Attribute::select('attribute_translations.title as title', 'attributes.id as id')
                    ->join('properties', 'attributes.property_id', '=', 'properties.id')
                    ->join('attribute_translations', 'attributes.id', '=', 'attribute_translations.attribute_id')
                    ->join('attribute_product', 'attributes.id', '=', 'attribute_product.attribute_id')
                    ->join('products', 'attribute_product.product_id', '=', 'products.id')
                    ->join('category_product', 'products.id', '=', 'category_product.product_id')
                    ->where('category_product.category_id', $category_id)->where('attributes.publish', 1)->where('products.publish', 1)
                    ->where('properties.id', $property->id)
                    ->groupBy('attributes.id')->orderBy('attributes.order', 'ASC')->get();
                return $property;
            });
        }
        return $properties;
    }

    public function attribute(){
        return $this->hasMany(Attribute::class);
    }

    public function category(){
        return $this->belongsToMany(Category::class);
    }

}
