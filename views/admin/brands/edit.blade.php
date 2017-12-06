@extends('admin.index')

@section('header')
    {!! HTML::style('admin/plugins/select2/css/select2.min.css') !!}
    {!! HTML::style('admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
@endsection

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/brands') }}">Brendovi</a></li>
        <li class="active">Izmena</li>
    </ol>
@endsection

@section('content')

    <div class="row">
        @include('admin.partials.errors')
        <div class="col-md-4">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="fa fa-sticky-note" style="margin-right: 5px"></i>Podaci o brendu</h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(['action' => ['BrandsController@update', $brand->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'files' => true]) !!}
                    <div class="form-group">
                        @if($brand->logo != null && $brand->logo != '')
                            <div class="place">
                                <img src="{{ url($brand->logo) }}" style="width: 100%; height: auto; margin-bottom: 10px">
                                <label for="logo" class="col-sm-3 control-label">Logo</label>
                                <div class="col-sm-9">
                                    {!! Form::file('logo') !!}
                                </div>
                            </div>
                        @else
                            <label for="logo" class="col-sm-3 control-label">Logo</label>
                            <div class="col-sm-9">
                                {!! Form::file('logo') !!}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        @if($brand->image != null && $brand->image != '')
                            <div class="place">
                                <img src="{{ url($brand->image) }}" style="width: 100%; height: auto; margin-bottom: 10px">
                                <label for="image" class="col-sm-3 control-label">Slika brenda</label>
                                <div class="col-sm-9">
                                    {!! Form::file('image') !!}
                                </div>
                            </div>
                        @else
                            <label for="image" class="col-sm-3 control-label">Slika brenda</label>
                            <div class="col-sm-9">
                                {!! Form::file('image') !!}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="order" class="col-sm-3 control-label">Redosled</label>
                        <div class="col-sm-9">
                            {!! Form::text('order', $brand->order, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="publish" class="col-sm-3 control-label">Vidljivo</label>
                        <div class="col-sm-9">
                            {!! Form::checkbox('publish', 1, $brand->publish, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'active']) !!}
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
                                {!! Form::open(['action' => ['BrandsController@updateLang', $brand->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                {!! Form::hidden('locale', $language->locale) !!}
                                <div class="form-group">
                                    <label for="title" class="col-sm-2 control-label">Naziv <span class="crvena-zvezdica">*</span></label>
                                    <div class="col-sm-10">
                                        {!! Form::text('title', $brand->{'title:'.$language->locale}, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="short" class="col-sm-2 control-label">SEO opis</label>
                                    <div class="col-sm-10">
                                        {!! Form::textarea('short', $brand->{'short:'.$language->locale}, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="body" class="col-sm-2 control-label">Ceo opis</label>
                                    <div class="col-sm-10">
                                        {!! Form::textarea('body', $brand->{'body:'.$language->locale}, array('class' => 'form-control', 'id' => 'body'.$br)) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="body2" class="col-sm-2 control-label">Prodajna mesta</label>
                                    <div class="col-sm-10">
                                        {!! Form::textarea('body2', $brand->{'body2:'.$language->locale}, array('class' => 'form-control', 'id' => 'bbody'.$br)) !!}
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
    <div class="row">
        <div class="col-md-12">

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

    @php $br=0; @endphp
    @if(count($languages)>0)
    window.onload = function () {

        @foreach($languages as $language)
        @php $br++; @endphp
            CKEDITOR.replace('body{{$br}}', {
                "filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
            });

            CKEDITOR.replace('bbody{{$br}}', {
                "filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
            });
        @endforeach
    };
    @endif

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