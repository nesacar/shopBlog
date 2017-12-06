@extends('admin.index')

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/users') }}">Korisnici</a></li>
        <li class="active">Kreiranje</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><i class="fa fa-user" style="margin-right: 5px"></i>Podaci o korisniku</h4>
                </div>
                <div class="panel-body">
                    @include('admin.partials.errors')
                    {!! Form::open(['action' => ['UsersController@store'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">Korisničko ime</label>
                            <div class="col-sm-10">
                                {!! Form::text('username', null, array('class' => 'form-control', 'id' => 'username')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">E-mail</label>
                            <div class="col-sm-10">
                                {!! Form::text('email', null, array('class' => 'form-control', 'id' => 'email')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Lozinka</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" id="password" placeholder="Lozinka..." value="" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="col-sm-2 control-label">Potvrda lozinke</label>
                            <div class="col-sm-10">
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Potvrda lozinke..." value="" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="block" class="col-sm-2 control-label">Blokiran</label>
                            <div class="col-sm-10">
                                {!! Form::checkbox('block', 1, null, ['class' => 'switch-state', 'data-on-color' => 'success', 'data-off-color' => 'danger', 'data-on-text' => 'DA', 'data-off-text' => 'NE', 'id' => 'active']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role" class="col-sm-2 control-label">Pravo pristupa</label>
                            <div class="col-sm-10">
                                @if(\Auth::user()->role == 5)
                                    {!! Form::select('role', [-1 => '--', 0 => 'Kupac', 1 => 'Urednik', 2 => 'Glavni urednik', 3 => 'Menager', 4 => 'Admin', 5 => 'Developer'], null, array('class' => 'sele', 'id' => 'role')) !!}
                                @else
                                    {!! Form::select('role', [-1 => '--', 0 => 'Kupac', 1 => 'Urednik', 2 => 'Glavni urednik', 3 => 'Menager', 4 => 'Admin'], null, array('class' => 'sele', 'id' => 'role')) !!}
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="customer">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Ime</label>
                                <div class="col-sm-10">
                                    {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="col-sm-2 control-label">Prezime</label>
                                <div class="col-sm-10">
                                    {!! Form::text('lastname', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="col-sm-2 control-label">Telefon</label>
                                <div class="col-sm-10">
                                    {!! Form::text('phone', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company" class="col-sm-2 control-label">Kompanija</label>
                                <div class="col-sm-10">
                                    {!! Form::text('company', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address" class="col-sm-2 control-label">Adresa</label>
                                <div class="col-sm-10">
                                    {!! Form::text('address', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="state" class="col-sm-2 control-label">Država</label>
                                <div class="col-sm-10">
                                    {!! Form::text('state', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="town" class="col-sm-2 control-label">Grad</label>
                                <div class="col-sm-10">
                                    {!! Form::text('town', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="postcode" class="col-sm-2 control-label">Poštanski broj</label>
                                <div class="col-sm-10">
                                    {!! Form::text('postcode', null, array('class' => 'form-control')) !!}
                                </div>
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

@section('footer')
    {!! HTML::script('admin/plugins/moment/moment.js') !!}
    {!! HTML::script('admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}
@endsection

@section('footer_scripts')
    var br=0;

    $('.switch-state').bootstrapSwitch();

    $('#role').change(function(){
        if($(this).val() == 0){
            $.get('{{ url("admin/users/candidate") }}', {_token: '{{ csrf_token() }}'}, function(data){
                $('.place').html(data);
            });
        }else if($(this).val() == 1){
            $.get('{{ url("admin/users/employer") }}', {_token: '{{ csrf_token() }}'}, function(data){
                $('.place').html(data);
            });
        }else{
            $('.place').html('');
        }
    });

    $('#birthday').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
    });

    $('#employer_od').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
    });

    $('#employer_do').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
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

    $('#role').change(function(){
        if($(this).val() == 0){
            $('.customer').fadeIn();
        }else{
            $('.customer').fadeOut();
        }
    });
@endsection