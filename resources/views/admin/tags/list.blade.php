@extends('layouts.admin')

@section('content')
<div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ Utils::translateModelDataPlural('blade_templates.admin.global.list_title') }} <a href="{{ route('tags.create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> @lang('blade_templates.global.create_new') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content form-horizontal">
                    <br />
                    @include('blocks.model', ['field' => 'tagType'])

                    <div class="ln_solid"></div>

                    <div id="tags-list"></div>
                    <button id="tags-reoder" class="btn btn-success" style="display: none;">@lang('blade_templates.admin.tags.reorder_list_button_text')</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection