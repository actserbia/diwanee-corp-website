<label for="{{ $name }}">{{ $title }}</label>
<select class="form-control" name="{{ $name }}" id="{{ $name }}" @if(!empty($required) && $required) required @endif>
    @if (!empty($tags))
        <option value=""></option>
        @foreach ($tags as $tag)
            @if ($tag->type === $name)
                <option value="{{ $tag->id }}" @if(!empty($selected) && $selected == $tag->id) selected @endif>{{ $tag->name }}</option>
            @endif
        @endforeach
    @endif
</select>