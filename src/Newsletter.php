<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model {

    protected $table = 'newsletters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'verification', 'received', 'send', 'skip', 'last_send', 'active'];

    public static function sendNewsletter($newsletter_id, $group_id){
        $theme = Theme::where('active', 1)->first();
        $newsletter = self::find($newsletter_id);
        $group = Group::find($group_id);
        if($group_id == 0){
            $subscribers = Subscriber::where('block', 0)->get();
        }else{
            $subscribers = $group->subscriber()->where('block', 0)->get();
        }
        if(count($subscribers)){
            foreach($subscribers as $s){
                \Mail::send('themes.'.$theme->slug.'.newsletters.newsletter-'.$newsletter->template_id, ['newsletter' => $newsletter, 'sub' => $s, 'preview' => false], function($message) use ($s, $newsletter)
                {
                    $message->to($s->email)->subject($newsletter->title);
                });
            }
        }
        $newsletter->send = 1;
        $newsletter->last_send = \Carbon\Carbon::now();
        $newsletter->received = $newsletter->received + count($subscribers);
        $newsletter->update();
    }


    public static function getProductTemplate1($products, $preview=false, $s_veri, $n_veri, $current=0){
        $str = '';

        $str .= '<tr>
                    <td>
                        <table width="728" height="442" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center" style="background-color: #FFFFFF; height: 442px;">
                            <tr>
                                <td width="18" border="0" style="height: 442px;">&nbsp;</td>
                                <td width="332" border="0" style="padding: 11px 31px; height: 442px; position: relative;">';
                                    if($preview){
                                        $newsletter = Newsletter::where('verification', $n_veri)->first();
                                        $num = Click::where('newsletter_id', $newsletter->id)->where('product_id', $products[$current]->id)->count();
                                        $str .= '<div class="prikaz"><a href="'.url('admin/newsletters/products/'.$n_veri.'/'.$products[$current]->id).'" target="_blank">'.$num.'</a></div>';
                                    }
                                    $str .= '<a href="'.url(Product::getProductLink($products[$current]->id).'?email='.$s_veri.'&news='.$n_veri).'"><img src="'.url($products[$current]->image).'" width="266" height="355" border="0" alt="" style="display: block;"/></a>
                                    <p style="color: #1b252e; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 10px; text-transform: uppercase; text-align: center; margin-bottom: 2px;">'.$products[$current]->title.'</p>
                                    <p style="color: #999999; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 12px; text-align: center; padding-top: 0;">'.$products[$current]->brend->title.'</p>
                                </td>
                                <td width="20" border="0" style="height: 442px;">&nbsp;</td>
                                <td width="342" border="0" style="height: 442px;">
                                    <table width="342" height="214" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center" style="background-color: #FFFFFF; height: 214px;">
                                        <tr>
                                            <td width="161" style="padding: 11px 31px; position: relative;">';
                                                if($preview){
                                                    $newsletter = Newsletter::where('verification', $n_veri)->first();
                                                    $num = Click::where('newsletter_id', $newsletter->id)->where('product_id', $products[$current+1]->id)->count();
                                                    $str .= '<div class="prikaz"><a href="'.url('admin/newsletters/products/'.$n_veri.'/'.$products[$current+1]->id).'" target="_blank">'.$num.'</a></div>';
                                                }
                                                $str .= '<a href="'.url(Product::getProductLink($products[$current+1]->id).'?email='.$s_veri.'&news='.$n_veri).'"><img src="'.url($products[$current+1]->image).'" width="102" height="135" border="0" alt="" style="display: block;"/></a>
                                                <p style="color: #1b252e; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 10px; text-transform: uppercase; text-align: center; margin-bottom: 2px;">'.$products[$current+1]->title.'</p>
                                                <p style="color: #999999; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 12px; text-align: center; padding-top: 0;">'.$products[$current+1]->brend->title.'</p>
                                            </td><td width="20">&nbsp;</td>
                                            <td width="161" style="padding: 11px 31px; position: relative;">';
                                                if($preview){
                                                    $newsletter = Newsletter::where('verification', $n_veri)->first();
                                                    $num = Click::where('newsletter_id', $newsletter->id)->where('product_id', $products[$current+2]->id)->count();
                                                    $str .= '<div class="prikaz"><a href="'.url('admin/newsletters/products/'.$n_veri.'/'.$products[$current+2]->id).'" target="_blank">'.$num.'</a></div>';
                                                }
                                                $str .= '<a href="'.url(Product::getProductLink($products[$current+2]->id).'?email='.$s_veri.'&news='.$n_veri).'"><img src="'.url($products[$current+2]->image).'" width="102" height="135" border="0" alt="" style="display: block;"/></a>
                                                <p style="color: #1b252e; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 10px; text-transform: uppercase; text-align: center; margin-bottom: 2px;">'.$products[$current+2]->title.'</p>
                                                <p style="color: #999999; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 12px; text-align: center; padding-top: 0;">'.$products[$current+2]->brend->title.'</p>
                                            </td>
                                        </tr>
                                    </table>
                                    <table width="342" height="15" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center" style="background-color: #FFFFFF; height: 15px;">
                                        <tr style="height: 15px;">
                                            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                                        </tr>
                                    </table>
                                    <table width="342" height="214" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center" style="background-color: #FFFFFF; height: 214px;">
                                        <tr>
                                            <td width="161" style="padding: 11px 31px; position: relative;">';
                                                if($preview){
                                                    $newsletter = Newsletter::where('verification', $n_veri)->first();
                                                    $num = Click::where('newsletter_id', $newsletter->id)->where('product_id', $products[$current+3]->id)->count();
                                                    $str .= '<div class="prikaz"><a href="'.url('admin/newsletters/products/'.$n_veri.'/'.$products[$current+3]->id).'" target="_blank">'.$num.'</a></div>';
                                                }
                                                $str .= '<a href="'.url(Product::getProductLink($products[$current+3]->id).'?email='.$s_veri.'&news='.$n_veri).'"><img src="'.url($products[$current+3]->image).'" width="102" height="135" border="0" alt="" style="display: block;"/></a>
                                                <p style="color: #1b252e; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 10px; text-transform: uppercase; text-align: center; margin-bottom: 2px;">'.$products[$current+3]->title.'</p>
                                                <p style="color: #999999; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 12px; text-align: center; padding-top: 0;">'.$products[$current+3]->brend->title.'</p>
                                            </td><td width="20">&nbsp;</td>
                                            <td width="161" style="padding: 11px 31px; position: relative;">';
                                                if($preview){
                                                    $newsletter = Newsletter::where('verification', $n_veri)->first();
                                                    $num = Click::where('newsletter_id', $newsletter->id)->where('product_id', $products[$current+4]->id)->count();
                                                    $str .= '<div class="prikaz"><a href="'.url('admin/newsletters/products/'.$n_veri.'/'.$products[$current+4]->id).'" target="_blank">'.$num.'</a></div>';
                                                }
                                                $str .= '<a href="'.url(Product::getProductLink($products[$current+4]->id).'?email='.$s_veri.'&news='.$n_veri).'"><img src="'.url($products[$current+4]->image).'" width="102" height="135" border="0" alt="" style="display: block;"/></a>
                                                <p style="color: #1b252e; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 10px; text-transform: uppercase; text-align: center; margin-bottom: 2px;">'.$products[$current+4]->title.'</p>
                                                <p style="color: #999999; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 12px; text-align: center; padding-top: 0;">'.$products[$current+4]->brend->title.'</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="18" border="0">
                                    &nbsp;
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>';

        return $str;
    }

    public static function getProductTemplate2($products, $preview=false, $s_veri, $n_veri, $current=5){
        $str='';

        $str .= '<tr>
                    <td>
                        <table width="728" height="275" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center" style="background-color: #FFFFFF; height: 275px;">
                            <tr>
                                <td width="18">
                                    &nbsp;
                                </td>
                                <td width="163" style="padding: 18px 12px; position: relative;">';
                                    if($preview){
                                        $newsletter = Newsletter::where('verification', $n_veri)->first();
                                        $num = Click::where('newsletter_id', $newsletter->id)->where('product_id', $products[$current]->id)->count();
                                        $str .= '<div class="prikaz"><a href="'.url('admin/newsletters/products/'.$n_veri.'/'.$products[$current]->id).'" target="_blank">'.$num.'</a></div>';
                                    }
                                    $str .= '<a href="'.url(Product::getProductLink($products[$current]->id).'?email='.$s_veri.'&news='.$n_veri).'"><img src="'.url($products[$current]->image).'" width="139" height="185" border="0" alt="" style="display: block;"/></a>
                                    <p style="color: #1b252e; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 10px; text-transform: uppercase; text-align: center; margin-bottom: 2px;">'.$products[$current]->title.'</p>
                                    <p style="color: #999999; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 12px; text-align: center; padding-top: 0;">'.$products[$current]->brend->title.'</p>
                                </td>
                                <td width="13">
                                    &nbsp;
                                </td>
                                <td width="163" style="padding: 18px 12px; position: relative;">';
                                    if($preview){
                                            $newsletter = Newsletter::where('verification', $n_veri)->first();
                                            $num = Click::where('newsletter_id', $newsletter->id)->where('product_id', $products[$current+1]->id)->count();
                                            $str .= '<div class="prikaz"><a href="'.url('admin/newsletters/products/'.$n_veri.'/'.$products[$current+1]->id).'" target="_blank">'.$num.'</a></div>';
                                    }
                                    $str .= '<a href="'.url(Product::getProductLink($products[$current+1]->id).'?email='.$s_veri.'&news='.$n_veri).'"><img src="'.url($products[$current+1]->image).'" width="139" height="185" border="0" alt="" style="display: block;"/></a>
                                    <p style="color: #1b252e; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 10px; text-transform: uppercase; text-align: center; margin-bottom: 2px;">'.$products[$current+1]->title.'</p>
                                    <p style="color: #999999; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 12px; text-align: center; padding-top: 0;">'.$products[$current+1]->brend->title.'</p>
                                </td>
                                <td width="13">
                                    &nbsp;
                                </td>
                                <td width="163" style="padding: 18px 12px; position: relative;">';
                                    if($preview){
                                        $newsletter = Newsletter::where('verification', $n_veri)->first();
                                        $num = Click::where('newsletter_id', $newsletter->id)->where('product_id', $products[$current+2]->id)->count();
                                        $str .= '<div class="prikaz"><a href="'.url('admin/newsletters/products/'.$n_veri.'/'.$products[$current+2]->id).'" target="_blank">'.$num.'</a></div>';
                                    }
                                $str .= '<a href="'.url(Product::getProductLink($products[$current+2]->id).'?email='.$s_veri.'&news='.$n_veri).'"><img src="'.url($products[$current+2]->image).'" width="139" height="185" border="0" alt="" style="display: block;"/></a>
                                    <p style="color: #1b252e; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 10px; text-transform: uppercase; text-align: center; margin-bottom: 2px;">'.$products[$current+2]->title.'</p>
                                    <p style="color: #999999; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 12px; text-align: center; padding-top: 0;">'.$products[$current+2]->brend->title.'</p>
                                </td>
                                <td width="13">
                                    &nbsp;
                                </td>
                                <td width="163" style="padding: 18px 12px; position: relative">';
                                    if($preview){
                                        $newsletter = Newsletter::where('verification', $n_veri)->first();
                                        $num = Click::where('newsletter_id', $newsletter->id)->where('product_id', $products[$current+3]->id)->count();
                                        $str .= '<div class="prikaz"><a href="'.url('admin/newsletters/products/'.$n_veri.'/'.$products[$current+3]->id).'" target="_blank">'.$num.'</a></div>';
                                    }
                                    $str .= '<a href="'.url(Product::getProductLink($products[$current+3]->id).'?email='.$s_veri.'&news='.$n_veri).'"><img src="'.url($products[$current+3]->image).'" width="139" height="185" border="0" alt="" style="display: block;"/></a>
                                    <p style="color: #1b252e; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 10px; text-transform: uppercase; text-align: center; margin-bottom: 2px;">'.$products[$current+3]->title.'</p>
                                    <p style="color: #999999; text-decoration: none !important; font-family: Helvetica, sans-sarif; font-size: 12px; text-align: center; padding-top: 0;">'.$products[$current+3]->brend->title.'</p>
                                </td>
                                <td width="18">
                                    &nbsp;
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>';

        return $str;
    }

    public static function getBannerTemplate1($banners, $preview=false, $s_veri, $n_veri, $current=0){
        $str='';

        $str .= '<tr>
                    <td>
                        <table width="728" height="120" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center" style="background-color: #FFFFFF; position: relative;">
                            <tr>
                                <td>';
                                    if($preview){
                                        $newsletter = self::where('verification', $n_veri)->first();
                                        $num = Click::where('newsletter_id', $newsletter->id)->where('product_id', 0)->where('post_id', 0)->where('banner_id', $banners[$current]->id)->count();
                                        $str .= '<div class="prikaz"><a href="'.url('admin/newsletters/banners/'.$newsletter->verification.'/'.$banners[$current]->id).'" target="_blank">'.$num.'</a></div>';
                                    }
                                    $str .= '<a href="'.url('admin/banners/'.$banners[$current]->id.'/click?email='.$s_veri.'&news='.$n_veri).'" taget="_blank"><img src="'.url($banners[$current]->image).'" width="728" height="120" border="0" alt="" style="display: block;"/></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>';

        return $str;
    }

    public function product(){
        return $this->belongsToMany('App\Product');
    }

    public function post(){
        return $this->belongsToMany('App\Post');
    }

    public function template(){
        return $this->belongsTo('App\Newsletter_template');
    }

    public function subscriber(){
        return $this->belongsToMany('App\Subscriber');
    }

    public function banner(){
        return $this->belongsToMany('App\Banner');
    }

}
