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
                    <h2>@lang('blade_templates.admin.users.list_title') </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('blade_templates.global.id')</th>
                                <th>@lang('blade_templates.users.name')</th>
                                <th>@lang('blade_templates.users.email')</th>
                                <th>@lang('blade_templates.users.role')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </thead>
                        <tbody>

                        @if( isset($userdetail))
                        @foreach($userdetail as $user)
                        <tr @if($user['deleted_at']!=null) class="deleted" @endif>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ $user['role'] }}</td>
                            <td>
                                @if(Auth::user()->role == 'admin' && $user['deleted_at'] == null)
                                <a href="{{ route('users.edit', ['id' => $user['id']]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="@lang('blade_templates.global.edit')"></i> </a>
                                <a href="{{ route('users.show', ['id' => $user['id']]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="@lang('blade_templates.global.delete')"></i> </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop