@extends('layouts.app')

@section('heading')
    @lang('blade_templates.profile.profile_title')
@endsection

@section('content')
    <form method="post" action="{{ route('profile.update') }}" data-parsley-validate class="form-horizontal form-label-left">
        <img src="{{ Auth::user()->getAvatar() }}" alt="{{ Auth::user()->name }}" class="img-circle">
                        
        @include('blocks.form_input', ['name' => 'name', 'label' => __('blade_templates.users.name'), 'value' => Auth::user()->name, 'required' => true])
                        
        @include('blocks.form_input', ['name' => 'email', 'label' => __('blade_templates.users.email'), 'value' => Auth::user()->email, 'required' => true, 'readonly' => true])

        @include('blocks.form_input', ['name' => 'role', 'label' => __('blade_templates.users.role'), 'value' => Auth::user()->role, 'required' => true, 'readonly' => true])
                        
        <div class="ln_solid"></div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
                <input name="_method" type="hidden" value="PUT">
                <button type="submit" class="btn btn-primary">@lang('blade_templates.profile.edit_profile_button_text')</button>
            </div>
        </div>
    </form>
@endsection
