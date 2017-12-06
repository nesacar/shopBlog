@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li class="active">Kategorije prodavnice</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title">Kategorije prodavnice</h3>
                    <div class="panel-control">
                        <a href="{{ url('admin/categories/sortable') }}" data-toggle="tooltip" data-placement="top" title="" class="panel-reload" data-original-title="Redosled kategorija"><i class="fa fa-random"></i></a>
                        <a href="{{ url('admin/categories/create') }}" data-toggle="tooltip" data-placement="top" title="" class="panel-reload" data-original-title="Kreiraj kategoriju"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="panel-heading clearfix">
                    {!! Form::open(['action' => ['CategoriesController@search'], 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'pretraga']) !!}
                    <div class="form-group">
                        <div class="col-sm-12">
                            {!! Form::select('category_id', $cats, $category_id, array('class' => 'form-control', 'id' => 'promena')) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="panel-body">
                    <div class="panel-header-stats">
                        @if(count($categories) > 0)
                            <div class="row">
                                <div class="col-sm-5">
                                    <b>Naziv</b>
                                </div>
                                <div class="col-sm-2">
                                    <b>Redosled</b>
                                </div>
                                <div class="col-sm-2">
                                    <b>Vidljivo</b>
                                </div>
                                <div class="col-sm-3">
                                    <b class="pull-right">Uredi</b>
                                </div>
                            </div>
                            <hr>
                            @foreach($categories as $c)
                                <div class="row @if($c->publish == 0) crvena @endif">
                                    <div class="col-sm-5 vcenter">
                                        {{ $c->{'title:sr'} }}
                                    </div>
                                    <div class="col-sm-2 vcenter">
                                        {{ $c->order }}
                                    </div>
                                    <div class="col-sm-2 vcenter-2">
                                        {!! Form::checkbox('publish', 1, $c->publish, ['id' => $c->id, 'name' => 'primary[]', 'class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE']) !!}
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="btn-group pull-right" role="group" aria-label="...">
                                            <a  type="button" class="btn btn-success" href="{{ URL::action('CategoriesController@edit', $c->id) }}">uredi</a>
                                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="glyphicon glyphicon-triangle-bottom"></i></button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <li><a href="{{ URL::action('CategoriesController@properties', $c->id) }}">osobine</a></li>
                                                <li><a href="{{ URL::action('CategoriesController@delete', $c->id) }}" onclick="return confirm('Da li ste sigurni da hoćete da obrišete ovu kategoriju?')" title="Obrišite kategoriju">obriši</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    {!! str_replace('/?', '?', $categories->render()) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var sw = $('.switch-state');


    sw.bootstrapSwitch();

    sw.on('switchChange.bootstrapSwitch', function (e, data) {

    $('#myswitch').bootstrapSwitch('state', !data, true);
        var id = $(this).attr('id');
        var link = 'categories/publish/' + id;
        $.get(link, {id: id, val:data}, function($stat){ if($stat=='da'){ save(); $('#'+id).parent().parent().parent().parent().toggleClass('crvena'); }else{ error(); } });
    });

    @if(Session::has('done'))
        toastr["success"]("{{ Session::get('done') }}");
    @endif

    @if(Session::has('error'))
        toastr["error"]("{{ Session::get('error') }}");
    @endif

    function save(){
        toastr["success"]("Izmenjeno");
    }

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

    $('#promena').change(function(){
        $('#pretraga').submit();
    });

@endsection