@extends('templates.admin.layout')

@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit Category <a href="{{route('tags.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> Back </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('tags.update', ['id' => $tag->id]) }}" data-parsley-validate class="form-horizontal form-label-left">
                        @include('blocks.form_input', ['name' => 'name', 'label' => 'Name', 'value' => $tag->name, 'required' => true])

                        @include('blocks.form_select', ['name' => 'type', 'label' => 'Type', 'items' => $types, 'selected' => $tag->type, 'required' => true])


                        @include('blocks.form_multiple_tags', ['name' => 'parents', 'label' => 'Parents', 'tags' => $parentsList, 'selectedTags' => $tag->parents])

                        @include('blocks.form_multiple_tags', ['name' => 'children', 'label' => 'Children', 'tags' => $childrenList, 'selectedTags' => $tag->children])


                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <input name="_method" type="hidden" value="PUT">
                                <button type="submit" class="btn btn-success">Save Category Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tags.js') }}"></script>
@endpush
