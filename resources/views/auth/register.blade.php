@extends('layouts.app')

@section('heading')
    @lang('blade_templates.auth.register_title')
@endsection

@section('content')
    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        @include('blocks.model', ['field' => 'name'])
        
        @include('blocks.model', ['field' => 'email'])
        
        @include('blocks.model', ['field' => 'password', 'confirm' => true])
        
        <div class="form-group">
            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label') }}">
                <button type="submit" class="btn btn-primary">
                    @lang('blade_templates.auth.register_button_text')
                </button>
            </div>
        </div>
    </form>
@endsection
