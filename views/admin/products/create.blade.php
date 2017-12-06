@extends('admin.index')

@section('header')
    {!! HTML::style('admin/plugins/select2/css/select2.min.css') !!}
    {!! HTML::style('admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
@endsection

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/products') }}">Proizvodi</a></li>
        <li class="active">Kreiranje</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="glyphicon glyphicon-gift" style="margin-right: 5px"></i>Podaci o proizvodu</h4>
                </div>
                <div class="panel-body">
                    @include('admin.partials.errors')
                    {!! Form::open(['action' => ['ProductsController@store'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Naziv <span class="crvena-zvezdica">*</span></label>
                        <div class="col-sm-10">
                            {!! Form::text('title', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="publish_at" class="col-sm-2 control-label">Vidljivo od datuma <span class="crvena-zvezdica">*</span></label>
                        <div class="col-sm-10">
                            {!! Form::text('publish_at', null, array('class' => 'form-control', 'id' => 'publish_at')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="short" class="col-sm-2 control-label">Kratak opis <span class="crvena-zvezdica">*</span></label>
                        <div class="col-sm-10">
                            {!! Form::textarea('short', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="brand_id" class="col-sm-2 control-label">Brend</label>
                        <div class="col-sm-10">
                            {!! Form::select('brand_id', $brands, null, array('class' => 'sele')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="code" class="col-sm-2 control-label">Šifra proizvoda</label>
                        <div class="col-sm-10">
                            {!! Form::text('code', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="body" class="col-sm-2 control-label">Ceo opis</label>
                        <div class="col-sm-10">
                            {!! Form::textarea('body', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    @if(false)
                    <div class="form-group">
                        <label for="body2" class="col-sm-2 control-label">Dodatne informacije</label>
                        <div class="col-sm-10">
                            {!! Form::textarea('body2', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">Slika</label>
                        <div class="col-sm-10">
                            {!! Form::file('image', null , array('id' => 'image')) !!}
                        </div>
                    </div>
                    @if(false)
                    <div class="form-group">
                        <label for="tmb" class="col-sm-2 control-label">Mala slika</label>
                        <div class="col-sm-10">
                            {!! Form::file('tmb', null, array('id' => 'image')) !!}
                        </div>
                    </div>
                    @endif
                    @if($setting->colorDependence)
                    <div class="form-group">
                        <label for="color" class="col-sm-2 control-label">Boja</label>
                        <div class="col-sm-10">
                            {!! Form::select('color', $colors, null, array('class' => 'sele')) !!}
                        </div>
                    </div>
                    @endif
                    @if($setting->materialDependence)
                        <div class="form-group">
                            <label for="material" class="col-sm-2 control-label">Boja</label>
                            <div class="col-sm-10">
                                {!! Form::select('material', $materials, null, array('class' => 'sele')) !!}
                            </div>
                        </div>
                    @endif
                    <h3>Cene</h3>
                    <hr>
                    <div class="form-group">
                        <label for="price_small" class="col-sm-2 control-label">Maloprodajna cena <span class="crvena-zvezdica">*</span></label>
                        <div class="col-sm-10">
                            {!! Form::text('price_small', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="discount" class="col-sm-2 control-label">Popust</label>
                        <div class="col-sm-10">
                            {!! Form::text('discount', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price_outlet" class="col-sm-2 control-label">Outlet cena</label>
                        <div class="col-sm-10">
                            {!! Form::text('price_outlet', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <h3>Dostupnost</h3>
                    <hr>
                    <div class="form-group">
                        <label for="publish" class="col-sm-2 control-label">Vidljivo na sajtu</label>
                        <div class="col-sm-10">
                            {!! Form::checkbox('publish', 1, 0, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'publish']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="primary" class="col-sm-2 control-label">Istaknut proizvod</label>
                        <div class="col-sm-10">
                            {!! Form::checkbox('featured', 1, 0, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'featured']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="amount" class="col-sm-2 control-label">Dostupna količina</label>
                        <div class="col-sm-10">
                            {!! Form::text('amount', 1, array('class' => 'form-control', 'id' => 'amount')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category_id" class="col-sm-2 control-label">Kategorije <span class="crvena-zvezdica">*</span></label>
                        <div class="col-sm-10">
                            {!! \App\Category::getSortCategoryCheckbox(false, $catids) !!}
                        </div>
                    </div>
                    @if(false)
                    <div class="form-group">
                        <label for="prod" class="col-sm-2 control-label">Povezani proizvodi</label>
                        <div class="col-sm-10">
                            {!! Form::select('prod[]', $products, null, ['id' => 'prod', 'class' => 'form-control', 'multiple']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category_id" class="col-sm-2 control-label">Povezane kategorije</label>
                        <div class="col-sm-10">
                            {!! Form::select('cats[]', $cats, null, ['id' => 'cats', 'class' => 'form-control', 'multiple']) !!}
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="submit" class="btn btn-success pull-right" value="Dodaj">
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div><!-- .row -->
@endsection

@section('footer')
    {!! HTML::script('admin/plugins/select2/js/select2.min.js') !!}
    {!! HTML::script('admin/js/jquery.mjs.nestedSortable.js') !!}
    {!! HTML::script('admin/plugins/moment/moment.js') !!}
    {!! HTML::script('admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}
    {!! HTML::script('admin/ckeditor/ckeditor.js') !!}
@endsection

@section('footer_scripts')

    window.onload = function () {
    CKEDITOR.replace('body', {
    "filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
    });
    };

    var br=0;

    $('#publish_at').datetimepicker({
    format: 'YYYY-MM-DD HH:mm'
    });

    $('.switch-state').bootstrapSwitch();

    $("#prod").select2({
    'placeholder': 'Izaberi proizvod',
    'tags': 'true'
    });

    $("#cats").select2({
    'placeholder': 'Izaberi kategoriju',
    'tags': 'true'
    });

    @if (Session::has('done'))
        toastr["success"]("{{ Session::get('done') }}");

        toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
        }
    @endif

    $('input[name=discount]').change(function(){
    var popust = $(this).val();
    var cena = $('input[name=price_small]').val();
    if(cena == ''){
    $('input[name=price_small]').val(0);
    }
    var nova = cena - (cena * popust/100);
    $('input[name=price_outlet]').val(nova);
    });
@endsection