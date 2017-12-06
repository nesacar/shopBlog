@extends('admin.index')

@section('header')
    {!! HTML::style('admin/plugins/select2/css/select2.min.css') !!}
    {!! HTML::style('admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
@endsection

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/posts') }}">Članci</a></li>
        <li class="active">Kreiranje</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="fa fa-file-text-o" style="margin-right: 5px"></i>Podaci o članku</h4>
                </div>
                <div class="panel-body">
                    @include('admin.partials.errors')
                    {!! Form::open(['action' => ['PostsController@store'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Naziv <span class="crvena-zvezdica">*</span></label>
                            <div class="col-sm-10">
                                {!! Form::text('title', null, array('class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="publish_at" class="col-sm-2 control-label">Vreme publikovanja <span class="crvena-zvezdica">*</span></label>
                            <div class="col-sm-10">
                                {!! Form::text('publish_at', null, array('class' => 'form-control', 'id' => 'publish_at')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="p_category_id" class="col-sm-2 control-label">Kategorija</label>
                            <div class="col-sm-10">
                                {!! Form::select('p_category_id', $categories, null, array('class' => 'sele')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="short" class="col-sm-2 control-label">Kratak opis <span class="crvena-zvezdica">*</span></label>
                            <div class="col-sm-10">
                                {!! Form::textarea('short', null, array('class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="body" class="col-sm-2 control-label">Ceo opis</label>
                            <div class="col-sm-10">
                                {!! Form::textarea('body', null, array('class' => 'form-control', 'id' => 'body')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image" class="col-sm-2 control-label">Slika</label>
                            <div class="col-sm-10">
                                {!! Form::file('image') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="home" class="col-sm-2 control-label">Prikazuje se u početnoj strani</label>
                            <div class="col-sm-10">
                                {!! Form::checkbox('home', 1, null, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'active']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="publish" class="col-sm-2 control-label">Vidljivo</label>
                            <div class="col-sm-10">
                                {!! Form::checkbox('publish', 1, null, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'active']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="col-sm-2 control-label">Tagovi</label>
                            <div class="col-sm-10">
                                {!! Form::select('tags[]', $tags, null, ['id' => 'tag', 'class' => 'form-control', 'multiple']) !!}
                            </div>
                        </div>
                        <hr>
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
    {!! HTML::script('admin/plugins/moment/moment.js') !!}
    {!! HTML::script('admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}
    {!! HTML::script('admin/ckeditor/ckeditor.js') !!}
    {!! HTML::script('admin/plugins/select2/js/select2.min.js') !!}
@endsection

@section('footer_scripts')

    $('.switch-state').bootstrapSwitch();

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var sw = $('.switch-state');


    sw.bootstrapSwitch();

    window.onload = function () {
        CKEDITOR.replace('body', {
            "filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
        });
    };

    $('#publish_at').datetimepicker({format: 'YYYY-MM-DD HH:mm'});

    $("#tag").select2({
        'placeholder': 'Izaberi tag',
        'tags': 'true'
    });

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