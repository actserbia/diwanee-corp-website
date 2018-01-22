@extends('layouts.app')

@section('heading')
    @lang('blade_templates.auth.reset_password_title')
@endsection

@section('content')
    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}

        @include('blocks.model', ['field' => 'email'])
        
        <div class="form-group">
            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label') }}">
                <button type="submit" class="btn btn-primary">
                    @lang('blade_templates.auth.send_reset_password_link')
                </button>
            </div>
        </div>
    </form>
@endsection
