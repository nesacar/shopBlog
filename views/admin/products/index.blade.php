@extends('admin.index')

@section('header')
    {!! HTML::style('admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
@endsection

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li class="active">Proizvodi</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-gift" aria-hidden="true" style="margin-right: 5px"></i>Proizvodi</h3>
                    <div class="panel-control">
                        <a href="{{ url('admin/products/create') }}" data-toggle="tooltip" data-placement="top" title="" class="panel-reload" data-original-title="Kreiraj proizvod"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="row" style="background-color: white">
                    {!! Form::open(['action' => 'ProductsController@search', 'method' => 'POST', 'id' => 'form-add-setting']) !!}
                        <div class="col-md-12">
                            <div class="col-sm-2">
                                <input type="text" name="title" placeholder="Pretraga..." id="title" class="form-control input-sm" value="@if(Session::get('title')){{Session::get('title')}}@endif">
                            </div>
                            <div class="col-sm-2">
                                {!! \App\Category::getSortCategorySelectAdmin() !!}
                            </div>
                            <div class="col-sm-2">
                                <input type="text" placeholder="cena od..." name="od" id="od" maxlength="6" value="@if(Session::get('od')){{Session::get('od')}}@endif" class="form-control input-sm" style="margin-bottom: 10px;">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" placeholder=" cena do..." name="do" id="do" maxlength="6" value="@if(Session::get('do')){{Session::get('do')}}@endif" class="form-control input-sm">
                            </div>
                            <div class="col-sm-2">
                                <div class="btn-group" role="group">
                                    <input type="submit" value="Pretraga" id="submit" class="btn btn-success">
                                    <a class="btn btn-danger" href="{{ url('admin/products/clear') }}">X</a>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
                <div class="panel-body">
                    <div class="panel-header-stats">
                        @if(count($products) > 0)
                            <div class="row">
                                @if(false)
                                <div class="col-md-1">
                                    <b>--</b>
                                </div>
                                @endif
                                <div class="col-md-1">
                                    <b>ID</b>
                                </div>
                                <div class="col-md-3">
                                    <b>Naziv</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Šifra</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Slika</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Cena</b>
                                </div>
                                <div class="col-md-2">
                                    <b>Kategorija</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Vidljivo</b>
                                </div>
                                <div class="col-md-2">
                                    <b class="pull-right">Uredi</b>
                                </div>
                            </div>
                            <hr>
                            @foreach($products as $p)
                                <div class="row @if($p->publish == 0) crvena @endif">
                                    @if(false)
                                    <div class="col-md-1 ima-padding">
                                        {!! Form::checkbox('all[]', $p->id, null) !!}
                                    </div>
                                    @endif
                                    <div class="col-md-1 ima-padding">
                                        {{ $p->id }}
                                    </div>
                                    <div class="col-md-3 ima-padding">
                                        {{ $p->{'title:sr'} }}
                                    </div>
                                    <div class="col-md-1 ima-padding">
                                        {{ $p->code }}
                                    </div>
                                    <div class="col-md-1">
                                        @if($p->tmb != null || $p->tmb != '')
                                            {!! HTML::image($p->tmb, '', ['class' => 'thumb']) !!}
                                        @elseif($p->image != null || $p->image != '')
                                            {!! HTML::image($p->image, '', ['class' => 'thumb']) !!}
                                        @endif
                                    </div>
                                    <div class="col-md-1 ima-padding">
                                        {{ $p->price_small }} RSD
                                    </div>
                                    <div class="col-md-2 ima-padding">
                                        {{ \App\Product::getLastCategory($p->id) }}
                                    </div>
                                    <div class="col-md-1 ima-padding2">
                                        {!! Form::checkbox('publish', 1, $p->publish, ['id' => $p->id, 'name' => 'primary[]', 'class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE']) !!}
                                    </div>
                                    <div class="col-md-2 ima-padding2">
                                        <div class="btn-group pull-right" role="group" aria-label="...">
                                            <a  type="button" class="btn btn-success" href="{{ URL::action('ProductsController@edit', $p->id) }}" target="_blank">uredi</a>
                                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="glyphicon glyphicon-triangle-bottom"></i></button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <li><a href="{{ url('shop/'.\App\Product::getProductLink($p->id)) }}" target="_blank">pregled</a></li>
                                                <li><a href="{{ URL::action('ProductsController@cloneProduct', $p->id) }}">kloniraj</a></li>
                                                <li><a href="{{ URL::action('ProductsController@image', $p->id) }}">slike</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="{{ URL::action('ProductsController@delete', $p->id) }}" onclick="return confirm('Da li ste sigurni da hoćete da obrišete ovaj proizvod?')" title="Obrišite proizvod">obriši</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    {!! str_replace('/?', '?', $products->render()) !!}
                                </div>
                            </div>
                        @else
                            <p>Nema dostupnih proizvoda</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    {!! HTML::script('admin/plugins/moment/moment.js') !!}
    {!! HTML::script('admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}
@endsection

@section('footer_scripts')



    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var sw = $('.switch-state');


    sw.bootstrapSwitch();

    sw.on('switchChange.bootstrapSwitch', function (e, data) {

    $('#myswitch').bootstrapSwitch('state', !data, true);
        var id = $(this).attr('id');
        var link = 'products/publish/' + id;
        $.get(link, {id: id, val:data}, function($stat){ if($stat=='da'){ save(); $('#'+id).parent().parent().parent().parent().toggleClass('crvena'); }else{ error(); } });
    });

    @if(Session::has('done'))
        toastr["success"]("{{ Session::get('done') }}");
    @endif

    @if(Session::has('error'))
        toastr["error"]("{{ Session::get('error') }}");
    @endif

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

    function save(){
        toastr["success"]("Izmenjeno");
    }
    function error(){
        toastr["error"]("Došlo je do greške");
    }

    function resetSearchFilter(){
        window.location.href = "{{ url('admin/products/clear') }}";
    }

    function reset(){
        $('#title').val(''); $('#od').val(''); $('#do').val(''); $('#kategorija').val(0);
    }

@endsection