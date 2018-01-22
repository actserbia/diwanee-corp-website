@extends('layouts.admin')


@section('content')

<div class="row tile_count text-center">
    <div class="col-md-3 col-sm-4 col-xs-12 tile_stats_count">
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

@endsection