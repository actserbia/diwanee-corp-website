<link rel="stylesheet" href="{{ url('asset/sirtrevorjs/sir-trevor.css')}}" type="text/css">

<script src="{{ url('asset/sirtrevorjs/sir-trevor.js')}}" type="text/javascript"></script>


<script type="text/javascript">
    window.onload = function(e){

        SirTrevor.setDefaults({ uploadUrl: "/images",  iconUrl: "/asset/sirtrevorjs/sir-trevor-icons.svg" });

        window.editor = new SirTrevor.Editor({
            el:document.querySelector('.sir-trevor'),
            defaultType: 'Text',
            blockTypes: [ 'Text', 'List', 'Quote', 'Image', 'Video', 'Heading', 'SliderImage' ]
        });

    }
</script>

@extends('templates.admin.layout')

@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit article <a href="{{route('articles.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> Back </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('articles.update', ['id' => $article->id]) }}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        @include('blocks.form_input', ['name' => 'title', 'label' => 'Title', 'value' => $article->title, 'required' => true])

                        @include('blocks.form_input', ['name' => 'meta_title', 'label' => 'Meta Title', 'value' => $article->meta_title])
                        
                        @include('blocks.form_input', ['name' => 'meta_description', 'label' => 'Meta Description', 'value' => $article->meta_description])
                        
                        @include('blocks.form_input', ['name' => 'meta_keywords', 'label' => 'Meta Keywords', 'value' => $article->meta_keywords])
                        
                        @include('blocks.form_input', ['name' => 'content_description', 'label' => 'Content Description', 'value' => $article->content_description])

                        @include('blocks.tags', ['name' => 'publication', 'label' => 'Publication', 'tags' => $tags, 'selected' => !empty($article->publication) ? $article->publication->id : ''])

                        @include('blocks.tags', ['name' => 'brand', 'label' => 'Brand', 'tags' => $tags, 'selected' => !empty($article->brand) ? $article->brand->id : ''])

                        @include('blocks.tags', ['name' => 'type', 'label' => 'Type', 'tags' => $tags, 'selected' => !empty($article->type) ? $article->type->id : ''])

                        @include('blocks.tags', ['name' => 'category', 'label' => 'Category', 'tags' => $tags, 'selected' => !empty($article->category) ? $article->category->id : '', 'required' => true])

                        @include('blocks.tags', ['name' => 'subcategory', 'label' => 'Subcategory', 'tags' => $article->category->children])

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div id="selected-subcategories">
                                    @if (!empty($article->subcategories))
                                        @foreach ($article->subcategories as $tag)
                                            <div class="subcategory">{{ $tag->name }} <a href="#" class="subcategory-remove" data-id="{{ $tag->id }}">x</a></div>
                                        @endforeach
                                    @endif
                                </div>
                                <div id="selected-subcategories-hidden"></div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="status" name="status" class="form-control col-md-7 col-xs-12">
                                    @foreach ($status as $id => $name)
                                        <option value="{{ $id }}" @if ($article->status == $id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('status'))
                                    <span class="help-block">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image">Image</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                @if (!empty($article->image))
                                    <img src="/images/{{ $article->image }}" width="200px" />
                                @endif
                                <input type="file" id="image" name="image">
                                @if ($errors->has('image'))
                                    <span class="help-block">{{ $errors->first('image') }}</span>
                                @endif
                                <input type="hidden" name="_token" value="{{ $article->image }}">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">Content</label>
                          <div class="col-md-6 col-sm-6 col-xs-12">

                            <textarea id="content" name="content" class="sir-trevor editable">{{ $article->content }}</textarea>
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
                                <button type="submit" class="btn btn-success">Save article Changes</button>
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
    <script src="{{ asset('js/articles.js') }}"></script>
    <script src="{{ asset('js/sir-trevor-custom.js') }}"></script>
@endpush
