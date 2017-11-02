@extends('layouts.admin')

@section('content')
<div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.admin.articles.edit_article_title') <a href="{{ route('articles.index') }}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('blade_templates.global.back') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('articles.update', ['id' => $article->id]) }}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        
                        @include('blocks.form_input', ['name' => 'title', 'label' => __('blade_templates.articles.title'), 'value' => $article->title, 'required' => true])


                        @include('blocks.form_input', ['name' => 'meta_title', 'label' => __('blade_templates.articles.meta_title'), 'value' => $article->meta_title])
                        
                        @include('blocks.form_input', ['name' => 'meta_description', 'label' => __('blade_templates.articles.meta_description'), 'value' => $article->meta_description])
                        
                        @include('blocks.form_input', ['name' => 'meta_keywords', 'label' => __('blade_templates.articles.meta_keywords'), 'value' => $article->meta_keywords])
                        
                        @include('blocks.form_input', ['name' => 'content_description', 'label' => __('blade_templates.articles.content_description'), 'value' => $article->content_description])

                        @include('blocks.form_input', ['name' => 'external_url', 'label' => __('blade_templates.articles.external_url'), 'value' => $article->external_url])


                        @include('blocks.form_tags', ['name' => 'publication', 'label' => __('blade_templates.articles.publication'), 'tags' => $tags, 'selected' => !empty($article->publication) ? $article->publication->id : ''])

                        @include('blocks.form_tags', ['name' => 'brand', 'label' => __('blade_templates.articles.brand'), 'tags' => $tags, 'selected' => !empty($article->brand) ? $article->brand->id : ''])

                        @include('blocks.form_tags', ['name' => 'influencer', 'label' => __('blade_templates.articles.influencer'), 'tags' => $tags, 'selected' => !empty($article->influencer) ? $article->influencer->id : ''])

                        @include('blocks.form_tags', ['name' => 'category', 'label' => __('blade_templates.articles.category'), 'tags' => $tags, 'selected' => !empty($article->category) ? $article->category->id : '', 'required' => true])

                        @include('blocks.form_multiple_tags', ['name' => 'subcategories', 'label' => __('blade_templates.articles.subcategories'), 'items' => $article->category->children, 'selectedItems' => $article->subcategories])


                        @include('blocks.form_select', ['name' => 'status', 'label' => __('blade_templates.articles.status'), 'items' => $statuses, 'selected' => $article->status, 'required' => true])

                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                            <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element', 'admin') }}" for="content">@lang('blade_templates.articles.content')</label>
                            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label', 'admin') }}">

                                <textarea id="content" name="content" class="sir-trevor editable">{{ $article->editorContent }}</textarea>
                                @if ($errors->has('content'))
                                    <span class="help-block">{{ $errors->first('content') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label', 'admin') }}">
                                <input name="_method" type="hidden" value="PUT">
                                <button type="submit" class="btn btn-success">@lang('blade_templates.admin.articles.edit_article_button_text')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
