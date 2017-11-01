@extends('layouts.admin')

@push('stylesheets')
<link rel="stylesheet" href="{{ url('css/admin-custom.css')}}" type="text/css">
@endpush

@section('content')
<div class="">

    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.admin.articles.list_title') <a href="{{ route('articles.create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> @lang('blade_templates.global.create_new') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('blade_templates.global.id')</th>
                                <th>@lang('blade_templates.articles.title')</th>
                                <th>@lang('blade_templates.articles.status')</th>
                                <th>@lang('blade_templates.global.created')</th>
                                <th>@lang('blade_templates.articles.author')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>@lang('blade_templates.global.id')</th>
                                <th>@lang('blade_templates.articles.title')</th>
                                <th>@lang('blade_templates.articles.status')</th>
                                <th>@lang('blade_templates.global.created')</th>
                                <th>@lang('blade_templates.articles.author')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </tfoot>
                        <tbody>

                        @foreach($articles as $article)
                        <tr @if($article['deleted_at'] != null) class="deleted" @endif>
                            <td>{{ $article['id'] }}</td>
                            <td>{{ $article['title'] }}</td>
                            <td>{{ $statuses[$article['status']] }}</td>
                            <td>{{ $article['created_at'] }}</td>
                            <td>{{ $article['author']['name'] }}</td>
                            <td>
                                @if($article['deleted_at'] == null)
                                    <a href="{{ route('articles.edit', ['id' => $article['id']]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="@lang('blade_templates.global.edit')"></i> </a>
                                    <a href="{{ route('articles.show', ['id' => $article['id']]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="@lang('blade_templates.global.delete')"></i> </a>
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
@stop
