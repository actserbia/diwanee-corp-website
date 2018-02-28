@extends('layouts.admin')

@section('content')
<div>
    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.admin.search.node_lists') </h2>
                    <div class="clearfix"></div>
                </div>


                <div class="x_content">
                    <br />
                    <form id="search_form" method="post" action="{{ route('admin.search.node-lists') }}" data-parsley-validate class="form-horizontal form-label-left">
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
                                <th>@lang('models_labels.NodeList.name')</th>
                                <th>@lang('models_labels.NodeList.node_type_label')</th>
                                <th>@lang('blade_templates.global.created')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>@lang('blade_templates.global.id')</th>
                                <th>@lang('models_labels.NodeList.name')</th>
                                <th>@lang('models_labels.NodeList.node_type_label')</th>
                                <th>@lang('blade_templates.global.created')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @foreach($items as $object)
                            <tr @if($object->deleted_at != null) class="deleted" @endif>
                                <td>{{ $object->id }}</td>
                                <td>{{ $object->name }}</td>
                                <td>{{ $object->node_type->name }}</td>
                                <td>{{ $object->created_at }}</td>
                                <td>
                                    <a href="{{ route('node-lists.edit', ['id' => $object->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="@lang('blade_templates.global.edit')"></i> </a>
                                    <a href="{{ route('node-lists.show', ['id' => $object->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="@lang('blade_templates.global.delete')"></i> </a>
                                    <a href="{{ route('node-lists.view', ['id' => $object->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-list" title="@lang('blade_templates.global.view')"></i> </a>
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