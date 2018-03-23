@extends('layouts.admin')

@section('content')

<div class="row tile_count ">
    <div class="panel-heading">
        Result <small>({{ $nodes->count() }})</small>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="container">
                <form action="{{ url('/admin/search') }}" method="get">
                    <div class="form-group">
                        <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="Search..."
                            value="{{ request('q') }}"
                            />
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="container">
                @forelse ($nodes as $article)
                <article>
                    <h2>{{ $article->id }} / {{ $article->title }}  </h2>
                    <span>type: {{ $article->model_type->name }}</span>

                    <p class="tags">
                        @foreach ($article->tags as $tag)
                        #{{ $tag->name }}&nbsp;
                        @endforeach
                    </p>
                </article>
                <hr />
                @empty
                <p>No articles found</p>
                @endforelse
            </div>
        </div>
        <div><a href="/api/search?q={{request('q')}}">API Link</div>
    </div>
</div>

@endsection