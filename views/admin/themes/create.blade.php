@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/themes') }}">Teme</a></li>
        <li class="active">Izmena</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title">Podaci o temi</h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(['action' => ['ThemesController@store'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Naziv</label>
                            <div class="col-sm-10">
                                {!! Form::text('title', null, array('class' => 'form-control', 'id' => 'title')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="version" class="col-sm-2 control-label">Verzija</label>
                            <div class="col-sm-10">
                                {!! Form::text('version', null, array('class' => 'form-control', 'id' => 'version')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="author" class="col-sm-2 control-label">Autor</label>
                            <div class="col-sm-10">
                                {!! Form::text('author', null, array('class' => 'form-control', 'id' => 'author')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="author_address" class="col-sm-2 control-label">Web adresa autora</label>
                            <div class="col-sm-10">
                                {!! Form::text('author_address', null, array('class' => 'form-control', 'id' => 'author_address')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="author_email" class="col-sm-2 control-label">Email adresa autora</label>
                            <div class="col-sm-10">
                                {!! Form::text('author_email', null, array('class' => 'form-control', 'id' => 'author_email')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image" class="col-sm-2 control-label">Slika teme</label>
                            <div class="col-sm-10">
                                {!! Form::file('image', null, array('class' => 'form-control', 'id' => 'image')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="developer" class="col-sm-2 control-label">Programer</label>
                            <div class="col-sm-10">
                                {!! Form::text('developer', null, array('class' => 'form-control', 'id' => 'developer')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="publish" class="col-sm-2 control-label">Publikovano</label>
                            <div class="col-sm-10">
                                {!! Form::checkbox('publish', 1, null, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'publish']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="active" class="col-sm-2 control-label">Aktivna tema</label>
                            <div class="col-sm-10">
                                {!! Form::checkbox('active', 1, null, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'active']) !!}
                            </div>
                        </div>
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

@section('footer_scripts')
    var br=0;

    $('.switch-state').bootstrapSwitch();

    $('.new').click(function(e){
        e.preventDefault();
        $('.hidden').first().removeClass('hidden');
    });

    $('.for').click(function(e){
        e.preventDefault();
        br++;
        $('.foreign').first().removeClass('foreign');
        if(br == 2){
            $('.for').fadeOut();
        }
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
@endsection