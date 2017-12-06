<?php

namespace nesacar\blogshop;

use Illuminate\Support\ServiceProvider;

class BlogShopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //loading the routes files
        //require __DIR__ . '/routes/web.php';

        //define route to be published
        $this->publishes([
            __DIR__ . '/routes/web.php' => base_path('/routes/blogShop.php')
        ]);

        //define the path for the view files
        $this->loadViewsFrom(__DIR__ .'/../views', 'blog');

        //define the migrations which going to be published.
        $this->publishes([
            __DIR__ . '/migrations/' => database_path('migrations'),
        ], 'migrations');

        //define the logic which going to be published.
        $this->publishes([
            __DIR__ . '/Logic/' => base_path('app/Logic'),
        ], 'logic');

        //define the models which going to be published.
        $this->publishes([
            __DIR__ . '/Attribute.php' => base_path('app/Attribute.php'),
            __DIR__ . '/AttributeTranslation.php' => base_path('app/AttributeTranslation.php'),
            __DIR__ . '/Brand.php' => base_path('app/Brand.php'),
            __DIR__ . '/BrandTranslation.php' => base_path('app/BrandTranslation.php'),
            __DIR__ . '/Block.php' => base_path('app/Block.php'),
            __DIR__ . '/Box.php' => base_path('app/Box.php'),
            __DIR__ . '/BoxTranslation.php' => base_path('app/BoxTranslation.php'),
            __DIR__ . '/Cart.php' => base_path('app/Cart.php'),
            __DIR__ . '/Category.php' => base_path('app/Category.php'),
            __DIR__ . '/CategoryTranslation.php' => base_path('app/CategoryTranslation.php'),
            __DIR__ . '/Customer.php' => base_path('app/Customer.php'),
            __DIR__ . '/Images.php' => base_path('app/Images.php'),
            __DIR__ . '/Menu.php' => base_path('app/Menu.php'),
            __DIR__ . '/MenuLink.php' => base_path('app/MenuLink.php'),
            __DIR__ . '/MenuImage.php' => base_path('app/MenuImage.php'),
            __DIR__ . '/MenuImageTranslation.php' => base_path('app/MenuImageTranslation.php'),
            __DIR__ . '/Newsletter.php' => base_path('app/Newsletter.php'),
            __DIR__ . '/Payment.php' => base_path('app/Payment.php'),
            __DIR__ . '/PCategory.php' => base_path('app/PCategory.php'),
            __DIR__ . '/PCategoryTranslation.php' => base_path('app/PCategoryTranslation.php'),
            __DIR__ . '/Post.php' => base_path('app/Post.php'),
            __DIR__ . '/PostTranslation.php' => base_path('app/PostTranslation.php'),
            __DIR__ . '/Product.php' => base_path('app/Product.php'),
            __DIR__ . '/ProductTranslation.php' => base_path('app/ProductTranslation.php'),
            __DIR__ . '/Property.php' => base_path('app/Property.php'),
            __DIR__ . '/PropertyTranslation.php' => base_path('app/PropertyTranslation.php'),
            __DIR__ . '/Setting.php' => base_path('app/Setting.php'),
            __DIR__ . '/SettingTranslation.php' => base_path('app/SettingTranslation.php'),
            __DIR__ . '/Tag.php' => base_path('app/Tag.php'),
            __DIR__ . '/TagTranslation.php' => base_path('app/TagTranslation.php'),
            __DIR__ . '/Theme.php' => base_path('app/Theme.php'),
            __DIR__ . '/User.php' => base_path('app/User.php'),
            __DIR__ . '/Language.php' => base_path('app/Language.php'),
        ], 'models');

        //define the controllers which going to be published.
        $this->publishes([
            __DIR__ . '/Http/Controllers/' => base_path('app/Http/Controllers'),
        ], 'controllers');

        //define the middleware which going to be published.
        $this->publishes([
            __DIR__ . '/Http/Middleware/' => base_path('app/Http/Middleware'),
        ], 'middleware');

        //define the requests which going to be published.
        $this->publishes([
            __DIR__ . '/Http/Requests/' => base_path('app/Http/Requests'),
        ], 'requests');

        //define the views which going to be published.
        $this->publishes([
            __DIR__ . '/../views/' => base_path('resources/views'),
        ], 'views');

        //define the assets which going to be published.
        $this->publishes([
            __DIR__ . '/../assets/' => public_path('/'),
        ], 'assets');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('blogshop', function($app){
            return new blogshop;
        });
    }
}
