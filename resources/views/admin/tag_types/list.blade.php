@extends('layouts.admin')

@section('content')
<div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.admin.tag_types.list_title') <a href="{{ route('tag-types.create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> @lang('blade_templates.global.create_new') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('blade_templates.global.id')</th>
                                <th>@lang('models_labels.TagType.name')</th>
                                <th>@lang('blade_templates.global.created')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>@lang('blade_templates.global.id')</th>
                                <th>@lang('models_labels.TagType.name')</th>
                                <th>@lang('blade_templates.global.created')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @foreach($tagTypes as $tagType)
                        <tr>
                            <td>{{ $tagType->id }}</td>
                            <td>{{ $tagType->name }}</td>
                            <td>{{ $tagType->created_at }}</td>
                            <td>
                                <a href="{{ route('tag-types.edit', ['id' => $tagType->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="@lang('blade_templates.global.edit')"></i> </a>
                                <a href="{{ route('tag-types.show', ['id' => $tagType->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="@lang('blade_templates.global.delete')"></i> </a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection