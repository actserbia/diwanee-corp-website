@if(!empty($objects))
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
            @foreach($objects as $object)
                <tr>
                    <td>{{ $object->id }}</td>
                    <td>{{ $object->title }}</td>
                    <td>{{ $object->model_type->name }}</td>
                    <td>{{ $object->created_at }}</td>
                    <td>{{ $object->author->name }}</td>
                    <td>
                        <a href="{{ route('nodes.edit', ['id' => $object->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="@lang('blade_templates.global.edit')"></i> </a>
                        <a href="{{ route('nodes.show', ['id' => $object->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="@lang('blade_templates.global.delete')"></i> </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif