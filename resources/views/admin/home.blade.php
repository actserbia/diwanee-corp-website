@extends('layouts.admin')


@section('content')

<!-- page content -->
<!-- top tiles -->
<div class="row tile_count">
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-users"></i> {{ __('views.admin.dashboard.count_0') }}</span>
        <div class="count green">{{ $counts['users'] }}</div>
    </div>

    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user-times "></i> {{ __('views.admin.dashboard.count_2') }}</span>
        <div>
            <span class="count green">{{  $counts['users'] - $counts['users_inactive'] }}</span>
            <span class="count">/</span>
            <span class="count red">{{ $counts['users_inactive'] }}</span>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-lock"></i> {{ __('views.admin.dashboard.count_3') }}</span>
        <div>
            <span class="count green">{{  $counts['protected_pages'] }}</span>
        </div>
    </div>
</div>
<!-- /top tiles -->

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div id="log_activity" class="dashboard_graph">


            <div class="row x_title">
                <div class="col-md-6">
                    <h3>{{ __('views.admin.dashboard.sub_title_0') }}</h3>
                </div>
                <div class="col-md-6">
                    <div class="date_piker pull-right"
                         style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <span class="date_piker_label">
                                {{ \Carbon\Carbon::now()->addDays(-6)->format('F j, Y') }} - {{ \Carbon\Carbon::now()->format('F j, Y') }}
                            </span>
                        <b class="caret"></b>
                    </div>
                </div>
            </div>

            <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="chart demo-placeholder" style="width: 100%; height:460px;"></div>
            </div>


            <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                <div class="x_title">
                    <h2>{{ __('views.admin.dashboard.sub_title_1') }}</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-6">
                    <div>
                        <p>{{ __('views.admin.dashboard.log_level_0') }}</p>
                        <div class="">
                            <div class="progress progress_sm" style="width: 76%;">
                                <div class="progress-bar log-emergency" role="progressbar" data-transitiongoal="0"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p>{{ __('views.admin.dashboard.log_level_1') }}</p>
                        <div class="">
                            <div class="progress progress_sm" style="width: 76%;">
                                <div class="progress-bar log-alert" role="progressbar" data-transitiongoal="0"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p>{{ __('views.admin.dashboard.log_level_2') }}</p>
                        <div class="">
                            <div class="progress progress_sm" style="width: 76%;">
                                <div class="progress-bar log-critical" role="progressbar" data-transitiongoal="0"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p>{{ __('views.admin.dashboard.log_level_3') }}</p>
                        <div class="">
                            <div class="progress progress_sm" style="width: 76%;">
                                <div class="asdasdasd"></div>
                                <div class="progress-bar log-error" role="progressbar" data-transitiongoal="0"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p>{{ __('views.admin.dashboard.log_level_4') }}</p>
                        <div class="">
                            <div class="progress progress_sm" style="width: 76%;">
                                <div class="progress-bar log-warning" role="progressbar" data-transitiongoal="0"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p>{{ __('views.admin.dashboard.log_level_5') }}</p>
                        <div class="">
                            <div class="progress progress_sm" style="width: 76%;">
                                <div class="progress-bar log-notice" role="progressbar" data-transitiongoal="0"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p>{{ __('views.admin.dashboard.log_level_6') }}</p>
                        <div class="">
                            <div class="progress progress_sm" style="width: 76%;">
                                <div class="progress-bar log-info" role="progressbar" data-transitiongoal="0"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p>{{ __('views.admin.dashboard.log_level_7') }}</p>
                        <div class="">
                            <div class="progress progress_sm" style="width: 76%;">
                                <div class="progress-bar log-debug" role="progressbar" data-transitiongoal="0"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

</div>
<br />


@endsection

@section('scripts')
@parent
@endsection

@section('styles')
@parent
@endsection
