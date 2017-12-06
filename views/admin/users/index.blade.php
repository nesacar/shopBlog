@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li class="active">Korisnici</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title">Korisnici</h3>
                    <div class="panel-control">
                        <a href="{{ url('admin/users/create') }}" data-toggle="tooltip" data-placement="top" title="" class="panel-reload" data-original-title="Kreiraj korisnika"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel-header-stats">
                        {!! Form::open(['action' => ['UsersController@search'], 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'formica']) !!}
                        <div class="col-sm-4">{!! Form::text('text', \Session::get('user_text'), array('class' => 'sele')) !!}</div>
                        <div class="col-sm-4">{!! Form::select('role', $roles, \Session::get('user_role'), array('class' => 'sele', 'id' => 'set')) !!}</div>
                        <div class="col-sm-4">{!! Form::checkbox('publish', 1, \Session::get('user_publish'), array('id' => 'publish')) !!} Vidljivi</div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel-header-stats">
                        @if(count($users) > 0)
                            <div class="row">
                                <div class="col-md-2">
                                    <b>ID</b>
                                </div>
                                <div class="col-md-4">
                                    <b>Email</b>
                                </div>
                                <div class="col-md-2">
                                    <b>Registrovan</b>
                                </div>
                                <div class="col-md-2">
                                    <b>Status</b>
                                </div>
                                <div class="col-md-2">
                                    <b class="pull-right">Uredi</b>
                                </div>
                            </div>
                            <hr>
                            @foreach($users as $u)
                                <div class="row @if($u->block == 1) crvena @endif">
                                    <div class="col-md-2 vcenter">
                                        {{ $u->id }}
                                    </div>
                                    <div class="col-md-4 vcenter">
                                        {{ $u->email }}
                                    </div>
                                    <div class="col-md-2 vcenter">
                                        {{ \Jenssegers\Date\Date::parse($u->created_at)->diffForHumans() }}
                                    </div>
                                    <div class="col-md-2 vcenter">
                                        {{ \App\User::getRole($u->id) }}
                                    </div>
                                    <div class="col-md-2">
                                        <div class="btn-group pull-right" role="group" aria-label="...">
                                            <a  type="button" class="btn btn-success" href="{{ URL::action('UsersController@edit', $u->id) }}">uredi</a>
                                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="glyphicon glyphicon-triangle-bottom"></i></button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="{{ URL::action('UsersController@delete', $u->id) }}" onclick="return confirm('Da li ste sigurni da hoćete da obrišete ovog korisnika?')" title="Obrišite korisnika">obriši</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    {!! str_replace('/?', '?', $users->render()) !!}
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

    $('#set').change(function(){
        $('#formica').submit();
    });

    $('#publish').click(function(){
        $('#formica').submit();
    });

@endsection