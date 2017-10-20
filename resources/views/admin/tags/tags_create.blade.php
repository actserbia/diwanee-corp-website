@extends('templates.admin.layout')

@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Create Category <a href="{{route('tags.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> Back </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('tags.store') }}" data-parsley-validate class="form-horizontal form-label-left">

                        @include('blocks.form_input', ['name' => 'name', 'label' => 'Name', 'value' => Request::old('name') ?: '', 'required' => false])

                        @include('blocks.form_select', ['name' => 'type', 'label' => 'Type', 'items' => $types, 'selected' => Request::old('type') ?: '', 'required' => false])

                        @include('blocks.form_multiple_tags', ['name' => 'parents', 'label' => 'Parents'])

                        @include('blocks.form_multiple_tags', ['name' => 'children', 'label' => 'Children'])

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <button type="submit" class="btn btn-success">Create Category</button>
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
