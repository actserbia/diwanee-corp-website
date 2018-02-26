@extends('layouts.admin')

@section('content')
<div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ Utils::translateModelData('blade_templates.admin.global.create_title')}} <a href="{{ route('nodes.index') }}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('blade_templates.global.back') </a></h2>
                    <div class="clearfix"></div>
                </div>
                
                <div id="node-create" class="x_content form-horizontal">
                    <br />
                    @include('blocks.model', ['field' => 'model_type'])

                    <div class="ln_solid"></div>

                    <div id="node-fields"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
