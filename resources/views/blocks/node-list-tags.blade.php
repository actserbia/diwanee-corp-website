@foreach($object->getAutomaticRenderRelations() as $field)
    @include('blocks.model', ['field' => $field])
@endforeach