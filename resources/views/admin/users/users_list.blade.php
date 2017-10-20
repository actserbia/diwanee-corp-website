@extends('templates.admin.layout')

@push('stylesheets')
<link rel="stylesheet" href="{{ url('css/admin-custom.css')}}" type="text/css">
@endpush


@section('content')
<div class="">
    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Users </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
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
                                <a href="{{ route('users.edit', ['id' => $user['id']]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="Edit"></i> </a>
                                <a href="{{ route('users.show', ['id' => $user['id']]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="Delete"></i> </a>
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