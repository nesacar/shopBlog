@extends('admin.index')

@section('header')
    {!! HTML::style('admin/plugins/select2/css/select2.min.css') !!}
    {!! HTML::style('admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
@endsection

@section('bredcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('admin/menus') }}">Meniji</a></li>
        <li><a href="{{ url('admin/menus/'.$menu->id.'/edit') }}">{{ $menu->title }}</a></li>
        <li class="active">Izmena</li>
    </ol>
@endsection

@section('content')

    <div class="row" style="background-color: white">
        @include('admin.partials.errors')
        <div class="col-md-6">
            @if(count($pcategories)>0)
                <div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Kategorije bloga</h4>
                    </div>
                    <div class="panel-body">
                        <ul id="sortable2" class="connectedSortable sortable2">
                            @foreach($pcategories as $category)
                                <li id="list_{{$category->id}}" class="ui-state-default" data-attribute="[]">
                                    <div>
                                        {!! Form::hidden('categories[]', $category->id) !!}
                                        {!! Form::hidden('types[]', 2) !!}
                                        {{ $category->{'title:sr'} }}
                                        <span class="kat-dole">Kategorija bloga <i class="fa fa-times" aria-hidden="true"></i></span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(count($categories)>0)
                    <div class="panel panel-white">
                        <div class="panel-heading clearfix">
                            <h4 class="panel-title">Kategorije prodavnice</h4>
                        </div>
                        <div class="panel-body">
                            <ul id="sortable1" class="connectedSortable sortable2">
                                @foreach($categories as $category)
                                    <li id="list_{{$category->id}}" class="ui-state-default" data-attribute="[]">
                                        <div>
                                            {!! Form::hidden('categories[]', $category->id) !!}
                                            {!! Form::hidden('types[]', 1) !!}
                                            {{ $category->{'title:sr'} }}
                                            <span class="kat-dole">Kategorija prodavnice <i class="fa fa-times" aria-hidden="true"></i></span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
            @endif
        </div><!-- .col-md-6 -->
        <div class="col-md-6">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title">Linkovi menija</h4>
                </div>
                <div class="panel-body">
                    <ol id="sortable3" class="connectedSortable sortable sortable2">
                        @php $br=0; @endphp
                        @foreach($links as $category)
                            @php $br++; @endphp
                            @php $attributes = \DB::table('attribute_menu_link')->where('menu_link_id', $category->id)->pluck('attribute_id')->toArray(); @endphp
                            <li id="list_{{$category->cat_id}}" class="ui-state-default" data-attribute="[{{ implode(',', $attributes) }}]">
                                <div>
                                    {!! Form::hidden('categories[]', $category->cat_id) !!}
                                    {!! Form::hidden('types[]', $category->type) !!}
                                    {{ $category->title }}
                                    @if($category->desc != null)
                                        ({{ $category->desc }})
                                    @endif
                                    <span class="kat-dole">
                                        @if($category->type == 2)
                                            <a href="{{ url('admin/menus/'.$category->id.'/editLink') }}">Kategorija bloga</a>
                                        @elseif($category->type == 1)
                                            <a href="{{ url('admin/menus/'.$category->id.'/editLink') }}">Kategorija prodavnice</a>
                                        @else
                                            <a href="{{ url('admin/menus/'.$category->id.'/editLink') }}">Custom link</a>
                                        @endif
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                    </span>
                                </div>
                                @php $subcat = \App\MenuLink::where('publish', 1)->where('menu_id', $menu->id)->where('parent', $category->cat_id)->where('level', 2)->orderBy('order', 'ASC')->get(); @endphp
                                @if(count($subcat)>0)
                                    <ol>
                                        @foreach($subcat as $sub)
                                            @php $br++; @endphp
                                            @php $attributes = \DB::table('attribute_menu_link')->where('menu_link_id', $sub->id)->pluck('attribute_id')->toArray(); @endphp
                                            <li id="list_{{$sub->cat_id}}" class="ui-state-default" data-attribute="[{{ implode(',', $attributes) }}]">
                                                <div>
                                                    {!! Form::hidden('categories[]', $sub->cat_id) !!}
                                                    {!! Form::hidden('types[]', $sub->type) !!}
                                                    {{ $sub->title }}
                                                    @if($sub->desc != null)
                                                        ({{ $sub->desc }})
                                                    @endif
                                                    <span class="kat-dole">
                                                        @if($sub->type == 2)
                                                            <a href="{{ url('admin/menus/'.$sub->id.'/editLink') }}">Kategorija bloga</a>
                                                        @elseif($sub->type == 1)
                                                            <a href="{{ url('admin/menus/'.$sub->id.'/editLink') }}">Kategorija prodavnice</a>
                                                        @else
                                                            <a href="{{ url('admin/menus/'.$sub->id.'/editLink') }}">Custom link</a>
                                                        @endif
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                @php $subcat2 = \App\MenuLink::where('publish', 1)->where('menu_id', $menu->id)->where('parent', $sub->cat_id)->where('level', 3)->orderBy('order', 'ASC')->get(); @endphp
                                                @if(count($subcat2)>0)
                                                    <ol>
                                                        @foreach($subcat2 as $sub2)
                                                            @php $br++; @endphp
                                                            @php $attributes = \DB::table('attribute_menu_link')->where('menu_link_id', $sub2->id)->pluck('attribute_id')->toArray(); @endphp
                                                            <li id="list_{{$sub2->cat_id}}" class="ui-state-default" data-attribute="[{{ implode(',', $attributes) }}]">
                                                                <div>
                                                                    {!! Form::hidden('categories[]', $sub2->cat_id) !!}
                                                                    {!! Form::hidden('types[]', $sub2->type) !!}

                                                                    {{ $sub2->title }}
                                                                    @if($sub2->desc != null)
                                                                        ({{ $sub2->desc }})
                                                                    @endif
                                                                    <span class="kat-dole">
                                                                         @if($sub2->type == 2)
                                                                            <a href="{{ url('admin/menus/'.$sub2->id.'/editLink') }}">Kategorija bloga</a>
                                                                        @elseif($sub2->type == 1)
                                                                            <a href="{{ url('admin/menus/'.$sub2->id.'/editLink') }}">Kategorija prodavnice</a>
                                                                        @else
                                                                            <a href="{{ url('admin/menus/'.$sub2->id.'/editLink') }}">Custom link</a>
                                                                        @endif
                                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                @php $subcat3 = \App\MenuLink::where('publish', 1)->where('menu_id', $menu->id)->where('parent', $sub2->cat_id)->where('level', 4)->orderBy('order', 'ASC')->get(); @endphp
                                                                @if(count($subcat3)>0)
                                                                    <ol>
                                                                        @foreach($subcat3 as $sub3)
                                                                            @php $br++; @endphp
                                                                            @php $attributes = \DB::table('attribute_menu_link')->where('menu_link_id', $sub3->id)->pluck('attribute_id')->toArray(); @endphp
                                                                            <li id="list_{{$sub3->cat_id}}" class="ui-state-default" data-attribute="[{{ implode(',', $attributes) }}]">
                                                                                <div>
                                                                                    {!! Form::hidden('categories[]', $sub3->cat_id) !!}
                                                                                    {!! Form::hidden('types[]', $sub3->type) !!}

                                                                                    {{ $sub3->title }}
                                                                                    @if($sub3->desc != null)
                                                                                        ({{ $sub3->desc }})
                                                                                    @endif
                                                                                    <span class="kat-dole">
                                                                                        @if($sub3->type == 2)
                                                                                            <a href="{{ url('admin/menus/'.$sub3->id.'/editLink') }}">Kategorija bloga</a>
                                                                                        @elseif($sub3->type == 1)
                                                                                            <a href="{{ url('admin/menus/'.$sub3->id.'/editLink') }}">Kategorija prodavnice</a>
                                                                                        @else
                                                                                            <a href="{{ url('admin/menus/'.$sub3->id.'/editLink') }}">Custom link</a>
                                                                                        @endif
                                                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                                                    </span>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    </ol>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ol>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ol>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                    <button class="btn btn-success" id="submit"><i class="fa fa-spinner fa-spin" style="display: none"></i> &nbsp; Izmeni</button>
                    <button class="btn btn-info pull-right" id="custom">Add custom link</button>
                </div>
            </div>
        </div><!-- .col-md-6 -->
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="place" style="display: none">
                @if(isset($custom))
                    <li id="list_{{ $custom }}" class="ui-state-default" data-attribute="[]">
                        <div>
                            {!! Form::hidden('categories[]', $custom) !!}
                            {!! Form::hidden('types[]', 3) !!}
                            Custom link
                            <span class="kat-dole">
                            Custom link
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </span>
                        </div>
                    </li>
                @else
                    <li id="list_1000" class="ui-state-default">
                        <div>
                            {!! Form::hidden('categories[]', 1000) !!}
                            {!! Form::hidden('types[]', 3) !!}
                            Custom link
                            <span class="kat-dole">
                            Custom link
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </span>
                        </div>
                    </li>
                @endif
            </div>
        </div>
    </div><!-- .row -->

@endsection

@section('footer')
    {!! HTML::script('admin/plugins/jquery-ui/jquery-ui.min.js') !!}
    {!! HTML::script('admin/plugins/nested/jquery.mjs.nestedSortable.js') !!}
@endsection

@section('footer_scripts')

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

    $("#sortable1, #sortable2, #sortable3").sortable({
        connectWith: ".connectedSortable"
    }).disableSelection();


    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $('.sortable').nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div',
        maxLevels: 4
    });

    $("#submit").on('click', function(event){
        var data = $('.sortable').nestedSortable('toArray');
        console.log(data);
        var ids = [];
        var types = [];
        var attributes = [];
        $("#sortable3 input[name='categories[]']").each(function(){
            if($(this).val() != ''){
                ids.push($(this).val());
            }
        });
        $("#sortable3 input[name='types[]']").each(function(){
            if($(this).val() != ''){
                types.push($(this).val());
            }
        });
        $("#sortable3").find("li").each(function(){
            attributes.push($(this).attr('data-attribute'));
        });
        console.log('ids: ' + ids);
        console.log('types: ' + types);
        console.log('attributes: ' + attributes);
        $('.fa-spin').css({'display':'inline-block'});
        $.post('{{ url('admin/menus/'.$menu->id.'/sortable') }}', {sortable: data, ids: ids, types: types, attributes: attributes, _token: CSRF_TOKEN}, function(data){
            if(data == 'save'){ save(); setTimeout(function(){ location.reload(); }, 1000); }
        });
    });

    $('.fa-times').click(function(){
        $(this).parent().parent().parent().remove();
    });

    /*$('.fa-files-o').click(function(){
        var children  = $(this).parent().parent().clone();
        $(this).parent().parent().parent().prepend(children);
    });*/

    $('#custom').click(function(e){
        e.preventDefault();
        console.log('add custom link');
        var children = $('#place').children().clone();
        $('#sortable3').append(children);
        var children2 = $('#place').children()
        var oldID = children2.attr('id');
        var newID = oldID.substr(5);
        newID++;
        $('#place').find('input[name="categories[]"]').val(newID);
        children2.attr('id', 'item_' + newID);
    });
@endsection