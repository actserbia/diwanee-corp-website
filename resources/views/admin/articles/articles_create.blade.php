@extends('layouts.admin')

@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.admin.articles.create_article_title') <a href="{{ route('articles.index') }}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('blade_templates.global.back') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('articles.store') }}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        @include('blocks.form_input', ['name' => 'title', 'label' => __('blade_templates.articles.title'), 'value' => Request::old('title') ?: '', 'required' => true])
                        
                        
                        @include('blocks.form_input', ['name' => 'meta_title', 'label' => __('blade_templates.articles.meta_title'), 'value' => Request::old('meta_title') ?: ''])
                        
                        @include('blocks.form_input', ['name' => 'meta_description', 'label' => __('blade_templates.articles.meta_description'), 'value' => Request::old('meta_description') ?: ''])
                        
                        @include('blocks.form_input', ['name' => 'meta_keywords', 'label' => __('blade_templates.articles.meta_keywords'), 'value' => Request::old('meta_keywords') ?: ''])
                        
                        @include('blocks.form_input', ['name' => 'content_description', 'label' => __('blade_templates.articles.content_description'), 'value' => Request::old('content_description') ?: ''])

                        @include('blocks.form_input', ['name' => 'external_url', 'label' => __('blade_templates.articles.external_url'), 'value' => Request::old('external_url') ?: ''])
                        

                        @include('blocks.form_tags', ['name' => 'publication', 'label' => __('blade_templates.articles.publication'), 'tags' => $tags, 'selected' => Request::old('publication') ?: ''])

                        @include('blocks.form_tags', ['name' => 'brand', 'label' => __('blade_templates.articles.brand'), 'tags' => $tags, 'selected' => Request::old('brand')])

                        @include('blocks.form_tags', ['name' => 'influencer', 'label' => __('blade_templates.articles.influencer'), 'tags' => $tags, 'selected' => Request::old('influencer')])

                        @include('blocks.form_tags', ['name' => 'category', 'label' => __('blade_templates.articles.category'), 'tags' => $tags, 'selected' => Request::old('category'), 'required' => true])

                        @include('blocks.form_multiple_tags', ['name' => 'subcategories', 'label' => __('blade_templates.articles.subcategories')])

                        @include('blocks.form_select', ['name' => 'status', 'label' => __('blade_templates.articles.status'), 'items' => $statuses, 'selected' => '0', 'required' => true])

                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">@lang('blade_templates.articles.content')</label>
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
                                <button type="submit" class="btn btn-success">@lang('blade_templates.admin.articles.create_article_button_text')</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
