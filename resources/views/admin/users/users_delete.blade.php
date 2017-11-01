@extends('layouts.admin')

@section('content')
<div class="">
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('messages.templates.global.delete_title') <a href="{{ route('users.index') }}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('messages.templates.global.back') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p>@lang('messages.templates.global.delete_question', ['title' => '<strong>' . $user['name'] . '</strong>'])</p>

                    <form method="POST" action="{{ route('users.destroy', ['id' => 1]) }}">
                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                        <input name="_method" type="hidden" value="DELETE">
                        <button type="submit" class="btn btn-danger">@lang('messages.templates.global.delete_confirm_message')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop