@extends('layouts.app')

@section('content')
    @auth
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Edit Article</div>

                        <div class="panel-body">
                            <form id="form-article-edit" action="/admin/articles/{{ $article->id }}" method="POST">
                                {!! csrf_field() !!}
                                <input type="hidden" name="_method" value="PUT">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" value="{{ $article->title }}" required>

                                    <label for="content">Content</label>
                                    <textarea class="form-control" name="content">{{ !$article->elements->isEmpty() ? $article->elements[0]->content : '' }}</textarea>

                                    @include('blocks.tags', ['name' => 'publication', 'title' => 'Publication', 'tags' => $tags, 'selected' => !empty($article->publication) ? $article->publication->id : ''])

                                    @include('blocks.tags', ['name' => 'brand', 'title' => 'Brand', 'tags' => $tags, 'selected' => !empty($article->brand) ? $article->brand->id : ''])

                                    @include('blocks.tags', ['name' => 'type', 'title' => 'Type', 'tags' => $tags, 'selected' => !empty($article->type) ? $article->type->id : '', 'required' => true])

                                    @include('blocks.tags', ['name' => 'category', 'title' => 'Category', 'tags' => $tags, 'selected' => !empty($article->category) ? $article->category->id : '', 'required' => true])

                                    @include('blocks.tags', ['name' => 'subcategory', 'title' => 'Subcategory', 'tags' => $subcategories])
                                    <div id="selected-subcategories">
                                        @if (!empty($article->subcategories))
                                            @foreach ($article->subcategories as $tag)
                                                <div class="subcategory">{{ $tag->name }}<a href="#" class="subcategory-remove" data-id="{{ $tag->id }}">x</a></div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div id="selected-subcategories-hidden"></div>

                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        @foreach ($status as $id => $name)
                                            <option value="{{ $id }}" {{ $article->status == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-default">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        You don't have permissions to edit article!
    @endauth
@endsection

@push('scripts')
    <script src="{{ asset('js/articles.js') }}"></script>
@endpush
