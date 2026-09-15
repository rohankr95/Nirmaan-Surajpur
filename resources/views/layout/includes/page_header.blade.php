<section class="content-header">
    <div class="header-icon"><a href="{{ url()->previous() }}"><i class="pe-7s-left-arrow"></i></a></div>
{{--    <div class="header-icon"><i class="pe-7s-{{$icon??'home'}}"></i></div>--}}
    <div class="header-title">
        <small></small>
        <h1>{{ $title??ucwords( str_replace("-"," ", last(request()->segments()))) }}</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            @if(request()->segment(1))
                <li class="{{echo_active(request()->segment(2)=="")}}">{{ ucwords( str_replace("_"," ", request()->segment(1)) ) }}</li>
            @endif
            @if(request()->segment(2))
                <li class="active">{{ ucwords( str_replace("_"," ", request()->segment(2)) ) }}</li>
            @endif
        </ol>
    </div>
</section>
