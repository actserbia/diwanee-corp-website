<div class="panel panel-primary">
    <div class="panel-heading">
        Result <small>({{ $nodes->count() }})</small>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="container">
                <form action="{{ url('searchx') }}" method="get">
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
                    <h2>{{ $article->title }}</h2>

                    <p>{{ $article->model_type->name }}</body>

                    <p class="well">
                        @foreach ($article->tags as $tag)
                        {{ $tag->name }}&nbsp;
                        @endforeach
                    </p>
                </article>
                @empty
                <p>No articles found</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
