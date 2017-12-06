@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/tags') }}">Tagovi</a></li>
        <li class="active">Izmena</li>
    </ol>
@endsection

@section('content')

    <div class="row">
        @include('admin.partials.errors')
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="glyphicon glyphicon-th-list" style="margin-right: 5px"></i>Podaci o tagovi</h4>
                </div>
                @if(count($languages)>0)
                <div class="panel-body">
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
                                {!! Form::open(['action' => ['TagsController@updateLang', $tag->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                    {!! Form::hidden('locale', $language->locale) !!}
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Naziv</label>
                                        <div class="col-sm-10">
                                            {!! Form::text('title', $tag->{'title:'.$language->locale}, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <hr>
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
        var link = '{{ url("admin/categories/publish") }}/' + id;
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