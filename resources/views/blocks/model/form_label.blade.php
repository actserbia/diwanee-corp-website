@foreach ($object->formReadonlyText($field, isset($column) ? $column : null) as $value)
    {{ $value }}
@endforeach