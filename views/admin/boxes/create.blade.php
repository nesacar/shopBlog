@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/boxes') }}">Slajd</a></li>
        <li class="active">Kreiranje slajda</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="fa fa-th" style="margin-right: 5px"></i>Podaci o slajdu</h4>
                </div>
                <div class="panel-body">
                    @include('admin.partials.errors')
                    {!! Form::open(['action' => ['BoxesController@store'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Naslov</label>
                            <div class="col-sm-10">
                                {!! Form::text('title', null, array('class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="block_id" class="col-sm-2 control-label">Šablon</label>
                            <div class="col-sm-10">
                                {!! Form::select('block_id', $blocks, $block_id, array('class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subtitle" class="col-sm-2 control-label">Pod naslov</label>
                            <div class="col-sm-10">
                                {!! Form::text('subtitle', null, array('class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="link" class="col-sm-2 control-label">Link</label>
                            <div class="col-sm-10">
                                {!! Form::text('link', null, array('class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="button" class="col-sm-2 control-label">Tekst dugmeta</label>
                            <div class="col-sm-10">
                                {!! Form::text('button', null, array('class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image" class="col-sm-2 control-label">Slika</label>
                            <div class="col-sm-10">
                                {!! Form::file('image') !!}
                            </div>
                        </div><div class="form-group">
                            <label for="order" class="col-sm-2 control-label">Redosled</label>
                            <div class="col-sm-10">
                                {!! Form::text('order', null, array('class' => 'form-control')) !!}
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

@section('footer_scripts')
    var br=0;

    $('.switch-state').bootstrapSwitch();

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