@extends('admin.index')

@section('header')
    {!! HTML::style('admin/plugins/select2/css/select2.min.css') !!}
    {!! HTML::style('admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
@endsection

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/properties') }}">Osobine</a></li>
        <li class="active">Izmena</li>
    </ol>
@endsection

@section('content')

    <div class="row">
        @include('admin.partials.errors')
        <div class="col-md-4">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="fa fa-file-text-o" style="margin-right: 5px"></i>Podaci o osobini</h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(['action' => ['PropertiesController@update', $property->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
                    <div class="form-group">
                        <label for="publish" class="col-sm-3 control-label">Vidljivo</label>
                        <div class="col-sm-9">
                            {!! Form::checkbox('publish', 1, $property->publish, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'active']) !!}
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
            </div>
        </div><!-- .col-md-4 -->
        <div class="col-md-8">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title">Jezičke verzije</h4>
                </div>
                @if(count($languages)>0)
                <div class="panel-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        @php $br=0; @endphp
                        @foreach($languages as $language)
                            @php $br++; @endphp
                            <li role="presentation" @if($br==1) class="active" @endif><a href="#{{$language->locale}}" aria-controls="profile" role="tab" data-toggle="tab">{{$language->fullname}}</a></li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @php $br=0; @endphp
                        @foreach($languages as $language)
                            @php $br++; @endphp
                            <div role="tabpanel" class="tab-pane @if($br==1) active @endif" id="{{$language->locale}}">
                                {!! Form::open(['action' => ['PropertiesController@updateLang', $property->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                {!! Form::hidden('locale', $language->locale) !!}
                                <div class="form-group">
                                    <label for="title" class="col-sm-2 control-label">Naziv</label>
                                    <div class="col-sm-10">
                                        {!! Form::text('title', $property->{'title:'.$language->locale}, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input type="submit" class="btn btn-success pull-right" value="Izmeni">
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div><!-- #{{$language->locale}} -->
                        @endforeach

                    </div>
                </div>
                @endif
            </div><!-- .col-md-8 -->
        </div><!-- .col-md-8 -->
    </div>
    <div class="row" style="background-color: white;">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title">Atributi za osobinu: {{ $property->{'title:sr'} }}</h4>
                </div>
                <div class="panel-body">
                    <div class="panel-header-stats">
                        @if(count($attributes) > 0)
                            <div class="row">
                                <div class="col-md-4">
                                    <b>ID</b>
                                </div>
                                <div class="col-md-4">
                                    <b>Naziv</b>
                                </div>
                                <div class="col-md-4">
                                    <b class="pull-right">Uredi</b>
                                </div>
                            </div>
                            <hr>
                            @foreach($attributes as $a)
                                <div class="row @if($a->publish == 0) crvena @endif">
                                    <div class="col-md-4 vcenter">
                                        {{ $a->id }}
                                    </div>
                                    <div class="col-md-4 vcenter">
                                        {{ $a->{'title:sr'} }}
                                    </div>
                                    <div class="col-md-4">
                                        <div class="btn-group pull-right" role="group" aria-label="...">
                                            <a  type="button" class="btn btn-success" href="{{ URL::action('AttributesController@edit', $a->id) }}">uredi</a>
                                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="glyphicon glyphicon-triangle-bottom"></i></button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <li><a href="{{ URL::action('AttributesController@delete', $a->id) }}" onclick="return confirm('Da li ste sigurni da hoćete da obrišete ovaj atribut?')" title="Obrišite atribut">obriši</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <h3 style="margin-top: 20px">Sortiranje</h3>
                            <div>
                                {!! \App\Attribute::getSortAttribute($property->id) !!}
                            </div>
                            <div>
                                <button class="btn btn-success" id="izmeni">Izmeni</button>
                            </div>
                        @else
                            <p>Nema dostupnih atributa</p>
                        @endif
                    </div>
                </div>
            </div>
        </div><!-- .col-md-6 -->
    </div><!-- .row -->

@endsection

@section('footer')
    {!! HTML::script('admin/plugins/moment/moment.js') !!}
    {!! HTML::script('admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}
    {!! HTML::script('admin/ckeditor/ckeditor.js') !!}
    {!! HTML::script('admin/plugins/select2/js/select2.min.js') !!}
    {!! HTML::script('admin/plugins/jquery-ui/jquery-ui.min.js') !!}
    {!! HTML::script('admin/plugins/nested/jquery.mjs.nestedSortable.js') !!}
@endsection

@section('footer_scripts')

    $('.switch-state').bootstrapSwitch();

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var sw = $('.switch-state');


    sw.bootstrapSwitch();

    @php $br=0; @endphp
    @if(count($languages)>0)
    window.onload = function () {
        @foreach($languages as $language)
        @php $br++; @endphp
        CKEDITOR.replace('body{{$br}}', {
            "filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
        });
        @endforeach
    };
    @endif

    $('#publish_at').datetimepicker({format: 'YYYY-MM-DD HH:mm'});

    $("#tag").select2({
        'placeholder': 'Izaberi tag',
        'tags': 'true'
    });

    $("#tag2").select2({
        'placeholder': 'Izaberi tag',
        'tags': 'true'
    });

    $('.remove').click(function(e){
        e.preventDefault();
        var link = $(this).attr('href');
        var img = $(this).attr('data-img');
        var place = $(this).parent();
        if (confirm('Da li ste sigurni da hoćete da obrišete ovu sliku?')) {
            $.post(link, { _token: '{{ csrf_token() }}', img: img }, function(data){ place.html(data); });
        }
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

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $('ol.sortable').nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div',
        maxLevels: 1
    });

    $("#izmeni").on('click', function(e){
        e.preventDefault();
        var data = $('ol.sortable').nestedSortable('toArray');
        console.log(data);
        $.post('{{ url('admin/attributes/sortable') }}', {sortable: data, _token: CSRF_TOKEN}, function(data){ if(data == 'save'){ save(); } });
    });
@endsection