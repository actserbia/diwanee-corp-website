@extends('layouts.admin')

@section('content')
<div>
    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.admin.search.nodes') </h2>
                    <div class="clearfix"></div>
                </div>


                <div class="x_content form-horizontal">
                    <br />
                    <form id="search_form" method="post" action="{{ route('admin.search.nodes') }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}
                        
                        @include('blocks.model', ['field' => 'model_type', 'object' => $model])
                    
                        <div class="ln_solid"></div>
                        
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
                    @if (isset($items))
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('blade_templates.global.id')</th>
                                    <th>@lang('models_labels.Node.title')</th>
                                    <th>@lang('models_labels.Node.model_type_label')</th>
                                    <th>@lang('blade_templates.global.created')</th>
                                    <th>@lang('models_labels.Node.author_label')</th>
                                    <th>@lang('blade_templates.global.actions')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>@lang('blade_templates.global.id')</th>
                                    <th>@lang('models_labels.Node.title')</th>
                                    <th>@lang('models_labels.Node.model_type_label')</th>
                                    <th>@lang('blade_templates.global.created')</th>
                                    <th>@lang('models_labels.Node.author_label')</th>
                                    <th>@lang('blade_templates.global.actions')</th>
                                </tr>
                            </tfoot>

                            <tbody>
                                @foreach($items as $node)
                                    <tr @if($node->deleted_at != null) class="deleted" @endif>
                                        <td>{{ $node->id }}</td>
                                        <td>{{ $node->title }}</td>
                                        <td>{{ $node->model_type->name }}</td>
                                        <td>{{ $node->created_at }}</td>
                                        <td>{{ $node->author->name }}</td>
                                        <td>
                                            @if($node->deleted_at == null)
                                                <a href="{{ route('nodes.edit', ['id' => $node->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="@lang('blade_templates.global.edit')"></i> </a>
                                                <a href="{{ route('nodes.show', ['id' => $node->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="@lang('blade_templates.global.delete')"></i> </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection