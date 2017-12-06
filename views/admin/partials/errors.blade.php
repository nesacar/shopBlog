@if($errors->any())
    <ul class="alert alert-danger" id="errors">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
@if(\Session::has('error'))
    <ul class="alert alert-danger" id="errors">
        <li>{{ \Session::get('error') }}</li>
    </ul>
@endif
<div style="clear: both;"></div>