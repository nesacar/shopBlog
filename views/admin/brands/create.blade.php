@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/brands') }}">Brendovi</a></li>
        <li class="active">Kreiranje</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="fa fa-sticky-note" style="margin-right: 5px"></i>Podaci o brendu</h4>
                </div>
                <div class="panel-body">
                    @include('admin.partials.errors')
                    {!! Form::open(['action' => ['BrandsController@store'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Naziv</label>
                            <div class="col-sm-10">
                                {!! Form::text('title', null, array('class' => 'form-control', 'id' => 'title')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="logo" class="col-sm-2 control-label">Logo</label>
                            <div class="col-sm-10">
                                {!! Form::file('logo') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image" class="col-sm-2 control-label">Slika brenda</label>
                            <div class="col-sm-10">
                                {!! Form::file('image') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="short" class="col-sm-2 control-label">SEO brenda</label>
                            <div class="col-sm-10">
                                {!! Form::textarea('short', null, array('class' => 'form-control', 'id' => 'short')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="body" class="col-sm-2 control-label">Ceo brenda</label>
                            <div class="col-sm-10">
                                {!! Form::textarea('body', null, array('class' => 'form-control', 'id' => 'body')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="body2" class="col-sm-2 control-label">Prodajna mesta</label>
                            <div class="col-sm-10">
                                {!! Form::textarea('body2', null, array('class' => 'form-control', 'id' => 'body2')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order" class="col-sm-2 control-label">Redosled</label>
                            <div class="col-sm-10">
                                {!! Form::text('order', null, array('class' => 'form-control', 'id' => 'order')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="top" class="col-sm-2 control-label">Publikovano</label>
                            <div class="col-sm-10">
                                {!! Form::checkbox('publish', 1, null, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'top']) !!}
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

@section('footer')
    {!! HTML::script('admin/ckeditor/ckeditor.js') !!}
@endsection

@section('footer_scripts')
    var br=0;

    $('.switch-state').bootstrapSwitch();

    window.onload = function () {
        CKEDITOR.replace('body', {
            "filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
        });

        CKEDITOR.replace('body2', {
            "filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
        });
    };


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