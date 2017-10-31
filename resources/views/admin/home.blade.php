@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('messages.templates.admin.home.dashboard_title')</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @lang('messages.templates.admin.home.dashboard_text')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection