@foreach ($object->formReadonlyData($field, $withCategory) as $data)
    {{ $data['label'] }}
@endforeach