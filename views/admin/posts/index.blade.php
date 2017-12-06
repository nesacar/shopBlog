@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li class="active">Članci</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title">Članci</h3>
                    <div class="panel-control">
                        <a href="{{ url('admin/posts/create') }}" data-toggle="tooltip" data-placement="top" title="" class="panel-reload" data-original-title="Kreiraj članak"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="panel-heading clearfix">
                    {!! Form::open(['action' => ['PostsController@search'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('post_title', \Session::get('post_title'), array('class' => 'form-control')) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! Form::select('post_cat', $categories, \Session::get('post_cat'), array('class' => 'sele')) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! Form::submit('Pretraga', array('class' => 'btn btn-success')) !!}
                        </div>
                    {!! Form::close() !!}
                </div>
                <div class="panel-body">
                    <div class="panel-header-stats">
                        @if(count($posts) > 0)
                            <div class="row">
                                <div class="col-md-3">
                                    <b>Naziv</b>
                                </div>
                                <div class="col-md-3">
                                    <b>Kategorija</b>
                                </div>
                                <div class="col-md-3">
                                    <b>Vidljivo</b>
                                </div>
                                <div class="col-md-3">
                                    <b class="pull-right">Uredi</b>
                                </div>
                            </div>
                            <hr>
                            @foreach($posts as $p)
                                <div class="row @if($p->publish == 0) crvena @endif">
                                    <div class="col-md-3 vcenter">
                                        {{ $p->{'title:sr'} }}
                                    </div>
                                    <div class="col-md-3 vcenter">
                                        {{ $p->category->{'title:sr'} }}
                                    </div>
                                    <div class="col-md-3 vcenter-2">
                                        {!! Form::checkbox('publish', 1, $p->publish, ['id' => $p->id, 'name' => 'primary[]', 'class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE']) !!}
                                    </div>
                                    <div class="col-md-3">
                                        <div class="btn-group pull-right" role="group" aria-label="...">
                                            <a type="button" class="btn btn-success" href="{{ \App\Post::getPostLink($p) }}" target="_blank">pregled</a>
                                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="glyphicon glyphicon-triangle-bottom"></i></button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <li><a href="{{ URL::action('PostsController@edit', $p->id) }}">uredi</a></li>
                                                <li><a href="{{ URL::action('PostsController@delete', $p->id) }}" onclick="return confirm('Da li ste sigurni da hoćete da obrišete ovaj članak?')" title="Obrišite članak">obriši</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    {!! str_replace('/?', '?', $posts->render()) !!}
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
        var link = 'posts/publish/' + id;
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

@endsection