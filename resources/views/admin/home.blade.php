@extends('layouts.admin')


@section('content')

<!-- page content -->
<!-- top tiles -->
<div class="row tile_count">

    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-file-text "></i> {{ __('views.admin.dashboard.count_1') }}</span>
        <a href="{{ route('articles.index') }}">
        <div>
            <span class="count green">{{  $counts['articles'] }}</span>
            <span class="count">/</span>
            <span class="count red">{{ $counts['articles_deleted'] }}</span>
        </div>
        </a>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-hashtag"></i> {{ __('views.admin.dashboard.count_3') }}</span>
        <a href="{{ route('tags.index') }}"><div class="count green">{{ $counts['tags'] }}</div></a>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-users"></i> {{ __('views.admin.dashboard.count_2') }}</span>
        <a href="{{ route('users.index') }}">
        <div>
            <span class="count green">{{ $counts['users'] }}</span>
            <span class="count">/</span>
            <span class="count red">{{ $counts['users_inactive'] }}</span>
        </div>
        </a>
    </div>

</div>
<!-- /top tiles -->

@endsection


<!--<script src="{{ asset('js/dashboard.js') }}"></script>-->
<!--@stack('scripts')-->


