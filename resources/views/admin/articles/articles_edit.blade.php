@extends('layouts.admin')

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
                    <h2>@lang('messages.templates.articles.edit_article_title') <a href="{{route('articles.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('messages.templates.global.back') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('articles.update', ['id' => $article->id]) }}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        @include('blocks.form_input', ['name' => 'title', 'label' => __('messages.templates.articles.title'), 'value' => $article->title, 'required' => true])


                        @include('blocks.form_input', ['name' => 'meta_title', 'label' => __('messages.templates.articles.meta_title'), 'value' => $article->meta_title])
                        
                        @include('blocks.form_input', ['name' => 'meta_description', 'label' => __('messages.templates.articles.meta_description'), 'value' => $article->meta_description])
                        
                        @include('blocks.form_input', ['name' => 'meta_keywords', 'label' => __('messages.templates.articles.meta_keywords'), 'value' => $article->meta_keywords])
                        
                        @include('blocks.form_input', ['name' => 'content_description', 'label' => __('messages.templates.articles.content_description'), 'value' => $article->content_description])

                        @include('blocks.form_input', ['name' => 'external_url', 'label' => __('messages.templates.articles.external_url'), 'value' => $article->external_url])


                        @include('blocks.form_tags', ['name' => 'publication', 'label' => __('messages.templates.articles.publication'), 'tags' => $tags, 'selected' => !empty($article->publication) ? $article->publication->id : ''])

                        @include('blocks.form_tags', ['name' => 'brand', 'label' => __('messages.templates.articles.brand'), 'tags' => $tags, 'selected' => !empty($article->brand) ? $article->brand->id : ''])

                        @include('blocks.form_tags', ['name' => 'influencer', 'label' => __('messages.templates.articles.influencer'), 'tags' => $tags, 'selected' => !empty($article->influencer) ? $article->influencer->id : ''])

                        @include('blocks.form_tags', ['name' => 'category', 'label' => __('messages.templates.articles.category'), 'tags' => $tags, 'selected' => !empty($article->category) ? $article->category->id : '', 'required' => true])

                        @include('blocks.form_multiple_tags', ['name' => 'subcategories', 'label' => __('messages.templates.articles.subcategories'), 'items' => $article->category->children, 'selectedItems' => $article->subcategories])


                        @include('blocks.form_select', ['name' => 'status', 'label' => __('messages.templates.articles.status'), 'items' => $statuses, 'selected' => $article->status, 'required' => true])

                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                          <label class="control-label col-md-3 col-sm-2 col-xs-12" for="content">@lang('messages.templates.articles.content')</label>
                          <div class="col-md-6 col-sm-8 col-xs-12">

                            <textarea id="content" name="content" class="sir-trevor editable">{{ $article->editorContent }}</textarea>
                            @if ($errors->has('content'))
                            <span class="help-block">{{ $errors->first('content') }}</span>
                            @endif
                          </div>
                        </div>

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <input name="_method" type="hidden" value="PUT">
                                <button type="submit" class="btn btn-success">@lang('messages.templates.articles.edit_article_button_text')</button>
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
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/sir-trevor-custom.js') }}"></script>
@endpush
