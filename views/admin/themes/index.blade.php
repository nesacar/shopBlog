@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li class="active">Teme</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title">Teme</h4>
                    <div class="panel-control">
                        <a href="{{ url('admin/themes/create') }}" data-toggle="tooltip" data-placement="top" title="" class="panel-reload" data-original-title="Kreiraj temu"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    @if(count($themes) > 0)
                        @foreach($themes as $t)
                            <div class="col-sm-4 theme-img @if($t->active == 0) theme-img-deactive @endif">
                                @if($t->image != '')
                                    {!! HTML::image($t->image) !!}
                                @endif
                                <p>{{ $t->title }}</p>
                                @if($t->active == 1)
                                    <div class="text-center">
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a class="btn btn-danger" href="{{ url('admin/themes/'.$t->id.'/deactivate') }}">Deaktiviraj</a>
                                            <a class="btn btn-primary" href="{{ url('admin/themes/'.$t->id.'/edit') }}">Uredi</a>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a class="btn btn-success" href="{{ url('admin/themes/'.$t->id.'/activate') }}">Aktiviraj</a>
                                            <a class="btn btn-primary" href="{{ url('admin/themes/'.$t->id.'/edit') }}">Uredi</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div><!-- .row -->
@endsection

@section('footer_scripts')
    var br=0;

    $('.switch-state').bootstrapSwitch();

    $('.new').click(function(e){
        e.preventDefault();
        $('.hidden').first().removeClass('hidden');
    });

    $('.for').click(function(e){
        e.preventDefault();
        br++;
        $('.foreign').first().removeClass('foreign');
        if(br == 2){
            $('.for').fadeOut();
        }
    });

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