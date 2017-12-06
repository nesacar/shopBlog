@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/brands') }}">Brendovi</a></li>
        <li class="active">Kolekcije</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title"><i class="fa fa-sticky-note rokni-desno" aria-hidden="true" ></i>Kolekcije za brend {{ $brand->{'title:sr'} }}</h3>
                    <div class="panel-control">
                        <a href="{{ url('admin/brands/create') }}" data-toggle="tooltip" data-placement="top" title="" class="panel-reload" data-original-title="Kreiraj brend"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel-header-stats">
                        @if(count($collection->attribute) > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    <b>Naziv</b>
                                </div>
                            </div>
                            <hr>
                            {!! Form::open(['action' => ['BrandsController@attributeUpdate', $brand->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                            @foreach($collection->attribute as $a)
                                <div class="row @if($a->publish == 0) crvena @endif">
                                    <div class="col-md-12">
                                        @if(in_array($a->id, $ids))
                                            {!! Form::checkbox('attribute[]', $a->id, true) !!} &nbsp;&nbsp;&nbsp;
                                        @else
                                            {!! Form::checkbox('attribute[]', $a->id, false) !!} &nbsp;&nbsp;&nbsp;
                                        @endif
                                        {{ $a->{'title:sr'} }}
                                    </div>
                                </div>
                            @endforeach
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input type="submit" class="btn btn-success pull-right" value="Izmeni">
                                </div>
                            </div>
                            {!! Form::close() !!}
                        @else
                            <p>Nema dostupnih kolekcija</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')

    $('.switch-state').bootstrapSwitch();

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

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var sw = $('.switch-state');

    function save(){
        toastr["success"]("Izmenjeno");
    }
    function error(){
        toastr["error"]("Došlo je do greške");
    }

@endsection