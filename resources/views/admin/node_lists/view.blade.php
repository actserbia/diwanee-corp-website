@extends('layouts.admin')

@section('content')
<div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $object->name }} <a href="{{ route('node-lists.edit', ['id' => $object->id]) }}" class="btn btn-info btn"><i class="fa"></i> @lang('blade_templates.global.edit') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('blade_templates.global.id')</th>
                                <th>@lang('models_labels.Node.title')</th>
                                <th>@lang('models_labels.Node.model_type_label')</th>
                                <th>@lang('blade_templates.global.created')</th>
                                <th>@lang('models_labels.Node.author_label')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>@lang('blade_templates.global.id')</th>
                                <th>@lang('models_labels.Node.title')</th>
                                <th>@lang('models_labels.Node.model_type_label')</th>
                                <th>@lang('blade_templates.global.created')</th>
                                <th>@lang('models_labels.Node.author_label')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($object->items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->model_type->name }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->author->name }}</td>
                                    <td>
                                        <a href="{{ route('nodes.edit', ['id' => $item->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="@lang('blade_templates.global.edit')"></i> </a>
                                        <a href="{{ route('nodes.show', ['id' => $item->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="@lang('blade_templates.global.delete')"></i> </a>
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