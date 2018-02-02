@extends('layouts.admin')

@section('content')
<div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.global.delete_title') <a href="{{ url()->previous() }}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('blade_templates.global.back') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p>@lang('blade_templates.global.delete_question', ['title' => '<strong>' . $type->name . '</strong>'])</p>

                    <form method="POST" action="{{ route('types.destroy', ['id' => $type->id]) }}">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="DELETE">
                        <button type="submit" class="btn btn-danger">@lang('blade_templates.global.delete_confirm_message')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
