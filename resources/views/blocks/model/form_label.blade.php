@foreach ($object->formReadonlyData($field, isset($withCategory) ? $withCategory : false) as $data)
    {{ $data['label'] }}
@endforeach