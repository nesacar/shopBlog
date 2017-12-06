@extends('admin.index')

@section('header')
    {!! HTML::style('admin/plugins/select2/css/select2.min.css') !!}
    {!! HTML::style('admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
@endsection

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/menus') }}">Meniji</a></li>
        <li><a href="{{ url('admin/menus/'.$menu->id.'/editLinks') }}">{{ $menu->title }}</a></li>
        <li class="active">Izmena</li>
    </ol>
@endsection

@section('content')

    <div class="row">
        @include('admin.partials.errors')
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="fa fa-bars" style="margin-right: 5px"></i>Podaci o linku</h4>
                </div>
                <div class="panel-body">

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
                                        {!! Form::open(['action' => ['MenusController@editLinkUpdate', $link->id], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                                        {!! Form::hidden('locale', $language->locale) !!}
                                        <div class="form-group">
                                            <label for="title" class="col-sm-2 control-label">Naziv</label>
                                            <div class="col-sm-10">
                                                {!! Form::text('title', $link->title, array('class' => 'form-control', 'id' => 'title')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="link" class="col-sm-2 control-label">Link</label>
                                            <div class="col-sm-10">
                                                {!! Form::text('link', $link->link, array('class' => 'form-control', 'id' => 'prefix')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="sufix" class="col-sm-2 control-label">Sufix</label>
                                            <div class="col-sm-10">
                                                {!! Form::text('sufix', $link->sufix, array('class' => 'form-control', 'id' => 'sufix')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="desc" class="col-sm-2 control-label">Opis</label>
                                            <div class="col-sm-10">
                                                {!! Form::text('desc', $link->desc, array('class' => 'form-control', 'id' => 'desc')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            @if($link->image != null && $link->image != '')
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-10">
                                                    <img src="{{ url($link->image) }}" alt="{{ $link->title }}" style="width: 100%; height: auto; margin-bottom: 10px">
                                                </div>
                                                <label for="image" class="col-sm-2 control-label">Slika</label>
                                                <div class="col-sm-10">
                                                    {!! Form::file('image') !!}
                                                </div>
                                            @else
                                                <label for="image" class="col-sm-2 control-label">Slika</label>
                                                <div class="col-sm-10">
                                                    {!! Form::file('image') !!}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="tip" class="col-sm-2 control-label">Tip</label>
                                            <div class="col-sm-10">
                                                @if($link->type == 1)
                                                    {!! Form::text('tip', 'Kategorija prodavnice', array('class' => 'form-control', 'id' => 'tip', 'disabled' => true)) !!}
                                                @elseif($link->type == 2)
                                                    {!! Form::text('tip', 'Kategorija bloga', array('class' => 'form-control', 'id' => 'tip', 'disabled' => true)) !!}
                                                @else
                                                    {!! Form::text('tip', 'Custom link', array('class' => 'form-control', 'id' => 'tip', 'disabled' => true)) !!}
                                                @endif
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
                </div>
            </div>
        </div><!-- .col-md-12 -->
    </div>
    <div class="row" style="background-color: white;">
        <div class="col-md-12">
            @if(count($properties)>0)
                {!! Form::open(['action' => ['MenusController@menuLinksAttributesPost', $link->id], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                <div class="col-sm-3">
                    @foreach($properties as $property)
                        @php $attributes = \App\Attribute::where('property_id', $property->id)->where('publish', 1)->orderBy('order', 'ASC')->get(); @endphp
                        @if(count($attributes)>0)
                            <h4>{{ $property->title }}</h4>
                            @foreach($attributes as $attribute)
                                @if(in_array($attribute->id, $ids))
                                    <p>{!! Form::checkbox('attributes[]', $attribute->id, true) !!} {{ $attribute->title }}</p>
                                @else
                                    <p>{!! Form::checkbox('attributes[]', $attribute->id, false) !!} {{ $attribute->title }}</p>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>
                <hr>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-success" value="Izmeni">
                    </div>
                </div>
                {!! Form::close() !!}
            @endif
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
@endsection