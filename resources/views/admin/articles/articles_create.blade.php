@extends('templates.admin.layout')

@push('stylesheets')
    <link rel="stylesheet" href="{{ url('asset/sirtrevorjs/sir-trevor.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ url('css/sir-trevor-custom.css')}}" type="text/css">
@endpush

@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Create article <a href="{{ route('articles.index') }}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> Back </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('articles.store') }}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        @include('blocks.form_input', ['name' => 'title', 'label' => 'Title', 'value' => Request::old('title') ?: '', 'required' => true])
                        
                        
                        @include('blocks.form_input', ['name' => 'meta_title', 'label' => 'Meta Title', 'value' => Request::old('meta_title') ?: ''])
                        
                        @include('blocks.form_input', ['name' => 'meta_description', 'label' => 'Meta Description', 'value' => Request::old('meta_description') ?: ''])
                        
                        @include('blocks.form_input', ['name' => 'meta_keywords', 'label' => 'Meta Keywords', 'value' => Request::old('meta_keywords') ?: ''])
                        
                        @include('blocks.form_input', ['name' => 'content_description', 'label' => 'Content Description', 'value' => Request::old('content_description') ?: ''])

                        @include('blocks.form_input', ['name' => 'external_url', 'label' => 'External Url', 'value' => Request::old('external_url') ?: ''])
                        

                        @include('blocks.form_tags', ['name' => 'publication', 'label' => 'Publication', 'tags' => $tags, 'selected' => Request::old('publication') ?: ''])

                        @include('blocks.form_tags', ['name' => 'brand', 'label' => 'Brand', 'tags' => $tags, 'selected' => Request::old('brand')])

                        @include('blocks.form_tags', ['name' => 'influencer', 'label' => 'Influencer', 'tags' => $tags, 'selected' => Request::old('influencer')])

                        @include('blocks.form_tags', ['name' => 'category', 'label' => 'Category', 'tags' => $tags, 'selected' => Request::old('category'), 'required' => true])

                        @include('blocks.form_multiple_tags', ['name' => 'subcategories', 'label' => 'Subcategories'])


                        @include('blocks.form_select', ['name' => 'status', 'label' => 'Status', 'items' => $statuses, 'selected' => '0', 'required' => true])

                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">Content</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea id="content" name="content" class="sir-trevor editable">{{ Request::old('content') ?: '' }}</textarea>
                                @if ($errors->has('content'))
                                   <span class="help-block">{{ $errors->first('content') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <button type="submit" class="btn btn-success">Create article</button>
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
    <script src="{{ url('asset/sirtrevorjs/sir-trevor.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/sir-trevor.js') }}"></script>
    <script src="{{ asset('js/articles.js') }}"></script>
    <script src="{{ asset('js/sir-trevor-custom.js') }}"></script>
@endpush
