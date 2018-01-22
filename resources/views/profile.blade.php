@extends('layouts.app')

@section('heading')
    @lang('blade_templates.profile.profile_title')
@endsection

@section('content')
    <form method="post" action="{{ route('profile.update') }}" data-parsley-validate class="form-horizontal form-label-left">
        {{ csrf_field() }}
        
        <img src="{{ Auth::user()->getAvatar() }}" alt="{{ Auth::user()->name }}" class="img-circle">
        
        @include('blocks.model', ['field' => 'name'])
        
        @include('blocks.model', ['field' => 'email', 'readonly' => true])
        
        @include('blocks.model', ['field' => 'role', 'readonly' => true])
                  
        <div class="ln_solid"></div>

        <div class="form-group">
            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label') }}">
                <input name="_method" type="hidden" value="PUT">
                <button type="submit" class="btn btn-primary">@lang('blade_templates.profile.edit_profile_button_text')</button>
            </div>
        </div>
    </form>
@endsection
