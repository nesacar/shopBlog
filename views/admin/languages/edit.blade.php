@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/languages') }}">Jezici</a></li>
        <li class="active">Izmena</li>
    </ol>
@endsection

@section('content')

    <div class="row">
        @include('admin.partials.errors')
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="fa fa-flag-checkered" style="margin-right: 5px"></i>Podaci o jeziku</h4>
                </div>
                {!! Form::open(['action' => ['LanguagesController@update', $language->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Naziv *</label>
                    <div class="col-sm-10">
                        {!! Form::text('name', $language->name, array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="fullname" class="col-sm-2 control-label">Pun naziv *</label>
                    <div class="col-sm-10">
                        {!! Form::text('fullname', $language->fullname, array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="locale" class="col-sm-2 control-label">Lokalizacija *</label>
                    <div class="col-sm-10">
                        {!! Form::text('locale', $language->locale, array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="order" class="col-sm-2 control-label">Redosled</label>
                    <div class="col-sm-10">
                        {!! Form::text('order', $language->order, array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="publish" class="col-sm-2 control-label">Vidljivo</label>
                    <div class="col-sm-10">
                        {!! Form::checkbox('publish', 1, $language->publish, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'active']) !!}
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-success pull-right" value="Izmeni">
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div><!-- .col-md-12 -->
    </div>
    <div class="row">
        <div class="col-md-12">

        </div>
    </div><!-- .row -->

@endsection

@section('footer')
    {!! HTML::script('ckeditor/ckeditor.js') !!}
@endsection

@section('footer_scripts')

    window.onload = function () {
        CKEDITOR.replace('body1', {
            "filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
        });
        CKEDITOR.replace('body2', {
            "filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
        });
    };

    var br=0;

    $('.switch-state').bootstrapSwitch();

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var sw = $('.odgovori').find('.switch-state');


    sw.bootstrapSwitch();

    sw.on('switchChange.bootstrapSwitch', function (e, data) {

    $('#myswitch').bootstrapSwitch('state', !data, true);
        var id = $(this).attr('id');
        var link = '{{ url("admin/languages/publish") }}/' + id;
        $.get(link, {id: id, val:data}, function($stat){ if($stat=='da'){ save(); $('#'+id).parent().parent().parent().parent().toggleClass('crvena'); }else{ error(); } });
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