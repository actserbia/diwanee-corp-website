@extends('layouts.admin')

@section('content')
<div>
    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.admin.search.elements') </h2>
                    <div class="clearfix"></div>
                </div>


                <div class="x_content">
                    <br />
                    <form id="search_form" method="post" action="{{ route('admin.search.elements') }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}
                        
                        @include('blocks.search-list')

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label') }}">
                                <button type="submit" class="btn btn-success">@lang('blade_templates.admin.search.search_button_text')</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('blade_templates.global.id')</th>
                                <th>@lang('models_labels.Element.type')</th>
                                <th>@lang('models_labels.Element.nodes_label') @lang('models_labels.Node.title')</th>
                                <th>@lang('models_labels.Element.nodes_label') @lang('models_labels.Node.id')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>@lang('blade_templates.global.id')</th>
                                <th>@lang('models_labels.Element.type')</th>
                                <th>@lang('models_labels.Element.nodes_label') @lang('models_labels.Node.title')</th>
                                <th>@lang('models_labels.Element.nodes_label') @lang('models_labels.Node.id')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </tfoot>

                        <tbody>
                            @foreach($items as $object)
                                <tr @if($object->node->deleted_at != null) class="deleted" @endif>
                                    <td>{{ $object->id }}</td>
                                    <td>{{ $object->fieldValue('type') }}</td>
                                    <td>{{ $object->node->title }}</td>
                                    <td>{{ $object->node->id }}</td>
                                    <td>
                                        @if($object->node->deleted_at == null)
                                            <a href="{{ route('nodes.edit', ['id' => $object->node->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="@lang('blade_templates.global.edit_article')"></i> </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection