@extends('admin.index')

@section('header')
    {!! HTML::style('admin/plugins/dropzone/dropzone.min.css') !!}
@endsection

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/products') }}">Proizvodi</a></li>
        <li class="active">Slike</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-gift" aria-hidden="true" style="margin-right: 5px"></i>Slike proizvoda</h3>
                    <div class="panel-control">
                        <a href="{{ url('admin/products/'.$product->id.'/addmimage') }}" data-toggle="tooltip" data-placement="top" title="" class="klik" data-original-title="Kreiraj sliku"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel-header-stats">
                        <div class="row" style="margin-bottom: 20px;">
                            @if($product->image != '')
                                <div class="col-sm-2 bor active">
                                    <div class="form-group">
                                        {!! HTML::image($product->image, $product->title,  array('class' => 'thumb2')) !!}
                                    </div>
                                    @if($setting->colorDependence)
                                    <div class="form-group">
                                        {!! Form::label('maincolor', 'Boja') !!}
                                        {!! Form::select('maincolor', $colors, $product->color, ['class' => 'sele main']) !!}
                                    </div>
                                    @endif
                                </div>
                            @endif
                            @if(count($product->images) > 0)
                                @foreach($product->images as $i)
                                    <div class="col-sm-2" style="text-align: center">
                                        <div class="form-group">
                                            {!! HTML::image($i->file_path, $i->product->title,  array('class' => 'thumb2')) !!}
                                            <a href="{{ url('admin/products/'.$i->id.'/deleteimage') }}"><i class="fa fa-times rem" aria-hidden="true"></i></a>
                                        </div>
                                        @if($setting->colorDependence)
                                        <div class="form-group">
                                            {!! Form::label('color', 'Boja') !!}
                                            {!! Form::select('color', $colors, $i->color, ['class' => 'sele color', 'data-image' => $i->id]) !!}
                                        </div>
                                        @endif
                                        @if($setting->materialDependence)
                                        <div class="form-group">
                                            {!! Form::label('material', 'Materijal') !!}
                                            {!! Form::select('material', $materials, $i->material, ['class' => 'sele material', 'data-image' => $i->id]) !!}
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div><!-- .row -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-10">
                            <div class="jumbotron how-to-create" >

                                {!! Form::open(['action' => ['ImageController@postUpload'], 'method' => 'POST', 'files' => 'true', 'class' => 'dropzone', 'id'=>'real-dropzone']) !!}
                                {!! Form::hidden('product_id', $product->id) !!}
                                <div class="dz-message">

                                </div>

                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>

                                <div class="dropzone-previews" id="dropzonePreview"></div>

                                <h4 style="text-align: center;color:#428bca;">Prevucite slike ovde za upload  <span class="glyphicon glyphicon-hand-down"></span></h4>

                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>

                    <!-- Dropzone Preview Template -->
                    <div id="preview-template" style="display: none;">

                        <div class="dz-preview dz-file-preview">
                            <div class="dz-image"><img data-dz-thumbnail=""></div>

                            <div class="dz-details">
                                <div class="dz-size"><span data-dz-size=""></span></div>
                                <div class="dz-filename"><span data-dz-name=""></span></div>
                            </div>
                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                            <div class="dz-error-message"><span data-dz-errormessage=""></span></div>

                            <div class="dz-success-mark">
                                <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                    <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                                    <title>Check</title>
                                    <desc>Created with Sketch.</desc>
                                    <defs></defs>
                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                        <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>
                                    </g>
                                </svg>
                            </div>

                            <div class="dz-error-mark">
                                <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                    <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                                    <title>error</title>
                                    <desc>Created with Sketch.</desc>
                                    <defs></defs>
                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                        <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">
                                            <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>
                                        </g>
                                    </g>
                                </svg>
                            </div>

                        </div>
                    </div>
                    <!-- End Dropzone Preview Template -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    {!! HTML::script('admin/plugins/dropzone/dropzone.min.js') !!}
@endsection

@section('footer_scripts')

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var sw = $('.switch-state');


    sw.bootstrapSwitch();

    sw.on('switchChange.bootstrapSwitch', function (e, data) {

    $('#myswitch').bootstrapSwitch('state', !data, true);
        var id = $(this).attr('id');
        var link = 'products/publish/' + id;
        $.get(link, {id: id, val:data}, function($stat){ if($stat=='da'){ save(); $('#'+id).parent().parent().parent().parent().toggleClass('crvena'); }else{ error(); } });
    });

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

    function save(){
        toastr["success"]("Izmenjeno");
    }
    function error(){
        toastr["error"]("Došlo je do greške");
    }

    $('#my-modal').modal();

    $('.klik').click(function (e) {
        e.preventDefault();
        console.log('klik');
        $('#myModal').modal('show');
    });

    $('.rem').click(function(e){
        e.preventDefault();
        if(confirm('Da li ste sigurni da želite obrisati ovu sliku?')){
            var el = $(this);
            var link = el.parent().attr('href');
            $.post(link, {_token: '{{ csrf_token() }}'}, function(data){ if(data == 'done'){ el.parent().parent().parent().fadeOut(); }else{ alert('greska'); } });
        }
    });

    @if(false)

    $('.main').change(function(){
        var color = $(this).val();
        var link = '{{ url('admin/products/'.$product->id.'/changemaincolor') }}';
        $.get(link, {color: color}, function($stat){ if($stat=='da'){ save(); }else{ error(); } });
    });

    $('.color').change(function(){
        var color = $(this).val();
        var slika = $(this).attr('data-image');
        var link = '{{ url('admin/products/'.$product->id.'/changecolor') }}';
        $.get(link, {color: color, slika: slika}, function($stat){ if($stat=='da'){ save(); }else{ error(); } });
    });

    $('.material').change(function(){
        var material = $(this).val();
        var slika = $(this).attr('data-image');
        var link = '{{ url('admin/products/'.$product->id.'/changematerial') }}';
        $.get(link, {material: material, slika: slika}, function($stat){ if($stat=='da'){ save(); }else{ error(); } });
    });

    @endif

@endsection