@extends('templates.admin.layout')

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
                    <form method="post" action="{{ route('articles.store') }}" data-parsley-validate class="form-horizontal form-label-left">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('title') ?: '' }}" id="title" name="title" class="form-control col-md-7 col-xs-12" required>
                                @if ($errors->has('title'))
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">Content</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea id="content" name="content" class="form-control col-md-7 col-xs-12" name="content">{{ Request::old('content') ?: '' }}</textarea>
                                @if ($errors->has('content'))
                                    <span class="help-block">{{ $errors->first('content') }}</span>
                                @endif
                            </div>
                        </div>

                        @include('blocks.tags', ['name' => 'publication', 'title' => 'Publication', 'tags' => $tags, 'selected' => Request::old('publication') ?: ''])

                        @include('blocks.tags', ['name' => 'brand', 'title' => 'Brand', 'tags' => $tags, 'selected' => old('brand')])

                        @include('blocks.tags', ['name' => 'type', 'title' => 'Type', 'tags' => $tags, 'selected' => old('type')])

                        @include('blocks.tags', ['name' => 'category', 'title' => 'Category', 'tags' => $tags, 'selected' => old('category'), 'required' => true])

                        @include('blocks.tags', ['name' => 'subcategory', 'title' => 'Subcategory', 'tags' => []])

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div id="selected-subcategories"></div>
                                <div id="selected-subcategories-hidden"></div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="status" name="status" class="form-control col-md-7 col-xs-12">
                                    @foreach ($status as $id => $name)
                                        <option value="{{ $id }}" {{ Request::old('status') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('status'))
                                    <span class="help-block">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <input type="hidden" name="api_token" value="{{ $user->api_token }}">
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
    <script src="{{ asset('js/articles.js') }}"></script>
@endpush
