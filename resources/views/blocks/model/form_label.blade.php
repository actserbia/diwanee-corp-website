@foreach ($object->formReadonlyData($field, isset($column) ? $column : null) as $data)
    {{ $data['label'] }}
@endforeach