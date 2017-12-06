<ul class="menu accordion-menu">
    @php $setting = \App\Setting::first(); @endphp
    <li><a href="{{ url('/') }}" class="waves-effect waves-button" target="_blank"><span class="menu-icon fa fa-flag"></span><p>Sajt</p><span class="active-page"></span></a></li>
    <li class="@if(isset($slug) && $slug == 'home') active @endif"><a href="{{ url('home') }}" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-th-large"></span><p>Početna</p><span class="active-page"></span></a></li>
    <li class="droplink @if(isset($slug) && $slug == 'settings') active @endif"><a href="#" class="waves-effect waves-button"><span class="menu-icon fa fa-cog"></span><p>Postavke</p><span class="arrow"></span></a>
        <ul class="sub-menu">
            <li><a href="{{ url('admin/settings/1/edit') }}"><i class="fa fa-search rokni-desno"></i>SEO</a></li>
            <li><a href="{{ url('admin/themes') }}"><i class="fa fa-picture-o rokni-desno"></i>Teme</a></li>
            <li><a href="{{ url('admin/languages') }}"><i class="fa fa-flag-checkered rokni-desno"></i>Jezici</a></li>
            <li><a href="{{ url('admin/menus') }}"><i class="fa fa-bars rokni-desno"></i>Meniji</a></li>
        </ul>
    </li>
    <li class="droplink @if(isset($slug) && $slug == 'users') active @endif"><a href="#" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-user"></span><p>Korisnici</p><span class="arrow"></span></a>
        <ul class="sub-menu">
            <li><a href="{{ url('admin/users') }}"><i class="glyphicon glyphicon-th-list rokni-desno"></i>Pregled</a></li>
        </ul>
    </li>
    <li class="droplink @if(isset($slug) && $slug == 'blocks') active @endif"><a href="#" class="waves-effect waves-button"><span class="menu-icon fa fa-th-large"></span><p>Šabloni</p><span class="arrow"></span></a>
        <ul class="sub-menu">
            <li><a href="{{ url('admin/blocks') }}"><i class="fa fa-th-large rokni-desno"></i>Šabloni</a></li>
            <li><a href="{{ url('admin/boxes') }}"><i class="fa fa-th rokni-desno"></i>Slajdovi</a></li>
        </ul>
    </li>
    @if($setting->blog == 1)
    <li class="droplink @if(isset($slug) && $slug == 'posts') active @endif"><a href="#" class="waves-effect waves-button"><span class="menu-icon fa fa-file-text-o"></span><p>Članci</p><span class="arrow"></span></a>
        <ul class="sub-menu">
            <li><a href="{{ url('admin/posts') }}"><i class="glyphicon glyphicon-th-list rokni-desno"></i>Članci</a></li>
            <li><a href="{{ url('admin/pcategories') }}"><i class="glyphicon glyphicon-th-list rokni-desno"></i>Kategorije članaka</a></li>
            <li><a href="{{ url('filemanager/show') }}" target="_blank"><i class="glyphicon glyphicon-cd rokni-desno"></i>Fajlovi</a></li>
            <li><a href="{{ url('admin/tags') }}"><i class="fa fa-tags rokni-desno"></i>Tagovi</a></li>
        </ul>
    </li>
    @endif
    @if($setting->shop == 1)
    <li class="droplink @if(isset($slug) && $slug == 'shop') active @endif"><a href="#" class="waves-effect waves-button"><span class="menu-icon fa fa-shopping-basket"></span><p>Prodavnica</p><span class="arrow"></span></a>
        <ul class="sub-menu">
            <li><a href="{{ url('admin/products') }}"><i class="fa fa-gift rokni-desno"></i>Proizvodi</a></li>
            <li><a href="{{ url('admin/categories') }}"><i class="fa fa-list rokni-desno"></i>Kategorije shopa</a></li>
            <li><a href="{{ url('admin/brands') }}"><i class="fa fa-sticky-note rokni-desno"></i>Brendovi</a></li>
            <li><a href="{{ url('admin/properties') }}"><i class="fa fa-cube rokni-desno"></i>Osobine</a></li>
            <li><a href="{{ url('admin/attributes') }}"><i class="fa fa-cubes rokni-desno"></i>Attributi</a></li>
            <li><a href="{{ url('admin/carts') }}"><i class="fa fa-shopping-cart rokni-desno"></i>Korpe</a></li>
        </ul>
    </li>
    @endif
    @if($setting->newsletter == 1)
        <li class="droplink @if(isset($slug) && ($slug == 'newsletter')) active @endif"><a href="#" class="waves-effect waves-button"><span class="menu-icon fa fa-envelope"></span><p>Newsletter</p><span class="arrow"></span></a>
            <ul class="sub-menu">
                <li><a href="{{ url('admin/newsletters') }}"><i class="fa fa-envelope rokni-desno"></i>Newsletter</a></li>
                <li><a href="{{ url('admin/subscribers') }}"><i class="fa fa-users rokni-desno"></i>Pretplatnici</a></li>
                <li><a href="{{ url('admin/banners') }}"><i class="fa fa-picture-o rokni-desno"></i>Baneri</a></li>
            </ul>
        </li>
    @endif
    <li class="droplink hidden-lg hidden-md"><a href="{{ url('logout') }}" class="waves-effect waves-button"><span class="menu-icon fa fa-power-off"></span><p>Odjava</p><span class="arrow"></span></a>

    </li>
</ul>