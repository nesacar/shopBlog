@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/pcategories') }}">Kategorije članaka</a></li>
        <li class="active">Izmena</li>
    </ol>
@endsection

@section('content')

    <div class="row">
        @include('admin.partials.errors')
        <div class="col-md-4">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="glyphicon glyphicon-th-list" style="margin-right: 5px"></i>Podaci o kategoriji</h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(['action' => ['PCategoriesController@update', $pcategory->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'files' => true]) !!}
                    <div class="form-group">
                        <label for="parent" class="col-sm-2 control-label">Nad kategorija</label>
                        <div class="col-sm-10">
                            {!! \App\PCategory::getSortCategoryRadio(false, $catids) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="order" class="col-sm-2 control-label">Redosled</label>
                        <div class="col-sm-10">
                            {!! Form::text('order', $pcategory->order, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="publish" class="col-sm-2 control-label">Vidljivo</label>
                        <div class="col-sm-10">
                            {!! Form::checkbox('publish', 1, $pcategory->publish, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'active']) !!}
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
                @if(false)
                <div class="panel panel-white">
                    <div class="col-sm-12">
                        <h3>Slike kategorije</h3>
                        @if(count($category->image)>0)
                            <ul class="brands">
                                @foreach($category->image as $image)
                                    <li><img src="{{ url($image->file_path) }}" class="thumb"><i class="fa fa-remove clear" data-id="{{ $image->id }}"></i></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="col-sm-12" style="margin-bottom: 10px">
                        <h3>Upload slika za galeriju (1200x650)</h3>
                        @include('admin.categories.dropzone')
                    </div>
                    <div style="clear: both"></div>
                </div>
                @endif
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
                                {!! Form::open(['action' => ['PCategoriesController@updateLang', $pcategory->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                    {!! Form::hidden('locale', $language->locale) !!}
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Naziv</label>
                                        <div class="col-sm-10">
                                            {!! Form::text('title', $pcategory->{'title:'.$language->locale}, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="desc" class="col-sm-2 control-label">SEO opis</label>
                                        <div class="col-sm-10">
                                            {!! Form::textarea('desc', $pcategory->{'desc:'.$language->locale}, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="body" class="col-sm-2 control-label">Ceo opis</label>
                                        <div class="col-sm-10">
                                            {!! Form::textarea('body', $pcategory->{'body:'.$language->locale}, array('class' => 'form-control', 'id' => 'body')) !!}
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
    {!! HTML::script('admin/ckeditor/ckeditor.js') !!}
@endsection

@section('footer_scripts')

    var br=0;

    $('.switch-state').bootstrapSwitch();

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var sw = $('.odgovori').find('.switch-state');


    sw.bootstrapSwitch();

    sw.on('switchChange.bootstrapSwitch', function (e, data) {

    $('#myswitch').bootstrapSwitch('state', !data, true);
        var id = $(this).attr('id');
        var link = '{{ url("admin/categories/publish") }}/' + id;
        $.get(link, {id: id, val:data}, function($stat){ if($stat=='da'){ save(); $('#'+id).parent().parent().parent().parent().toggleClass('crvena'); }else{ error(); } });
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

    $('.clear').click(function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var place = $(this).parent();
        $.post('{{ url("admin/images/delete") }}', { _token: '{{ csrf_token() }}', id: id }, function(data){ if(data == 'yes'){ place.fadeOut(); }else{ console.log(data); } })
    });

    window.onload = function () {
        CKEDITOR.replace('body', {
            "filebrowserBrowseUrl": "{!! url('filemanager/show') !!}"
        });
    };
@endsection