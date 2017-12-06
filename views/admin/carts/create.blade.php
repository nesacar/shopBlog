@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/attributes') }}">Atributi</a></li>
        <li class="active">Kreiranje</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="fa fa-cubes" style="margin-right: 5px"></i>Podaci o atributu</h4>
                </div>
                <div class="panel-body">
                    @include('admin.partials.errors')
                    {!! Form::open(['action' => ['AttributesController@store'], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Naziv</label>
                            <div class="col-sm-10">
                                {!! Form::text('title', null, array('class' => 'form-control', 'id' => 'title')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="extra" class="col-sm-2 control-label">Dodatak</label>
                            <div class="col-sm-10">
                                {!! Form::text('extra', null, array('class' => 'form-control', 'id' => 'extra')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order" class="col-sm-2 control-label">Redosled</label>
                            <div class="col-sm-10">
                                {!! Form::text('order', null, array('class' => 'form-control', 'id' => 'order')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="property_id" class="col-sm-2 control-label">Osobina</label>
                            <div class="col-sm-10">
                                {!! Form::select('property_id', $properties, null, array('class' => 'sele', 'id' => 'property_id_id')) !!}
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