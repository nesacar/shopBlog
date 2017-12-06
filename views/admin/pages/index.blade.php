@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li class="active">Home</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        @if(false)
        <div class="col-md-12">
            <div class="widget">

            </div><!-- .widget -->
        </div><!-- .col-md-12 -->

        <div class="col-md-12">
            <div class="widget">
                <div class="col-sm-6">
                    <canvas id="myChart" width="400" height="250"></canvas>
                </div>
                <div class="col-sm-6"></div>
            </div><!-- .widget -->
        </div><!-- .col-md-12 -->
        @endif
        <div class="col-md-12">

        </div>
    </div>
@endsection

@section('footer')
    @if(false){!! HTML::script('admin/plugins/js/Chart.min.js') !!}@endif
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


        @if (Session::has('save'))
            $.notify({
                    message: '{{ \Session::get('save') }}'
                },{
                    type: 'success'
                });
        @endif

        @if (Session::has('error'))
            $.notify({
                    message: '{{ \Session::get('error') }}'
                },{
                    type: 'danger'
                });
        @endif

        function save(){
            $.notify({
                message: 'Izmenjeno'
            },{
                type: 'success'
            });
        }
        function error(){
            $.notify({
                message: 'Proizvod nije pronadjen.'
            },{
                type: 'danger'
            });
        }
    </script>
@endsection
