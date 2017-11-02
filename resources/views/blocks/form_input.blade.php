<div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
    <label class="control-label col-md-3 col-sm-2 col-xs-12" for="{{ $name }}">
        {{ $label }} @if(!empty($required) && $required)<span class="required">*</span>@endif
    </label>
    <div class="col-md-6 col-sm-8 col-xs-12">
        <input 
            @if(!empty($type)) type="{{ $type }}" @else type="text" @endif 
            value="{{ $value }}" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            class="form-control" 
            @if(!empty($required) && $required) required @endif 
            @if(!empty($readonly) && $readonly) readonly @endif 
        />
        @if ($errors->has($name))
            <span class="help-block">{{ $errors->first($name) }}</span>
        @endif
    </div>
</div>