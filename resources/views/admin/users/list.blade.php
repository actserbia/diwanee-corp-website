@extends('layouts.admin')

@section('content')
<div>
    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ Utils::translateModelDataPlural('blade_templates.admin.global.list_title') }} @if(Auth::user()->role == 'admin')<a href="{{ route('users.create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> @lang('blade_templates.global.create_new') </a>@endif</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('blade_templates.global.id')</th>
                                <th>@lang('models_labels.User.name')</th>
                                <th>@lang('models_labels.User.email')</th>
                                <th>@lang('models_labels.User.role')</th>
                                <th>@lang('blade_templates.global.actions')</th>
                            </tr>
                        </thead>
                        <tbody>

                        @if( isset($userdetail))
                        @foreach($userdetail as $user)
                        <tr @if($user->deleted_at!=null) class="deleted" @endif>
                            <td class="user-profile">{{ $user['id'] }}
                                <img src="{{ $user->getAvatar() }}" alt="{{$user->name}}" class="img-circle">
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ __('constants.UserRole.' . $user->role) }}</td>
                            <td>
                                @if(Auth::user()->role == 'admin' && $user['deleted_at'] == null)
                                <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="@lang('blade_templates.global.edit')"></i> </a>
                                <a href="{{ route('users.show', ['id' => $user->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="@lang('blade_templates.global.delete')"></i> </a>
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
@endsection