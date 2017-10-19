@extends('templates.admin.layout')

@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit user <a href="{{route('users.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> Back </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('users.update', ['id' => $user->id]) }}" data-parsley-validate class="form-horizontal form-label-left">


                        @include('blocks.form_input', ['name' => 'name', 'label' => 'Name', 'value' => $user->name, 'required' => true])

                        @include('blocks.form_input', ['name' => 'email', 'label' => 'Email', 'value' => $user->email, 'required' => true])


                        <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Role
                                <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="form-control col-md-7 col-xs-12" id="role" name="role" required >
                                @if (!empty($roles))
                                <option value=""></option>
                                @foreach ($roles as $role)
                                <option value="{{ $role }}" @if($user->role == $role) selected @endif>{{ $role }}</option>
                                @endforeach
                                @endif
                                </select>
                                @if ($errors->has('role'))
                                <span class="help-block">{{ $errors->first('role') }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <input name="_method" type="hidden" value="PUT">
                                <button type="submit" class="btn btn-success">Save User Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
