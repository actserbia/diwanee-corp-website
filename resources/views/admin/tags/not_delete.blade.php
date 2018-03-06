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
                    <p>@lang('blade_templates.admin.tags.not_delete_text', ['title' => '<strong>' . $object->name . '</strong>'])</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
