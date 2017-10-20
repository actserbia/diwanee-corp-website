<div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="{{ $name }}">{{ $label }} @if(!empty($required) && $required)<span class="required">*</span>@endif</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control col-md-7 col-xs-12" id="{{ $name }}" name="{{ $name }}" @if(!empty($required) && $required) required @endif>
            @if (isset($items))
                <option value=""></option>
                @foreach ($items as $item)
                    <option value="{{ $item[$itemKey] }}" @if(isset($selected) && $selected == $item[$itemKey]) selected @endif>{{ $item[$itemTitle] }}</option>
                @endforeach
            @endif
        </select>

        @if ($errors->has($name))
            <span class="help-block">{{ $errors->first($name) }}</span>
        @endif
    </div>
</div>