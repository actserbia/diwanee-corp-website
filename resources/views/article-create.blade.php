@extends('layouts.app')

@section('content')
    @auth
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Create Article</div>

                        <div class="panel-body">
                            <form id="form-article-create" action="/admin/articles" method="POST">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>

                                    <label for="content">Content</label>
                                    <textarea class="form-control" name="content">{{ old('content') }}</textarea>

                                    @include('blocks.tags', ['name' => 'publication', 'title' => 'Publication', 'tags' => $tags, 'selected' => old('publication')])

                                    @include('blocks.tags', ['name' => 'brand', 'title' => 'Brand', 'tags' => $tags, 'selected' => old('brand')])

                                    @include('blocks.tags', ['name' => 'type', 'title' => 'Type', 'tags' => $tags, 'selected' => old('type'), 'required' => true])

                                    @include('blocks.tags', ['name' => 'category', 'title' => 'Category', 'tags' => $tags, 'selected' => old('category'), 'required' => true])

                                    @include('blocks.tags', ['name' => 'subcategory', 'title' => 'Subcategory', 'tags' => []])
                                    <div id="selected-subcategories"></div>
                                    <div id="selected-subcategories-hidden"></div>

                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        @foreach ($status as $id => $name)
                                            <option value="{{ $id }}" {{ old('status') == $id ? 'selected' : '' }}>{{ $name }}</option>
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
        You don't have permissions to create article!
    @endauth
@endsection

@push('scripts')
    <script src="{{ asset('js/articles.js') }}"></script>
@endpush