@extends('templates.admin.layout')

@section('content')
<div class="">

    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Categories <a href="{{route('tags.create')}}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Create New </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @foreach($tags as $tag)
                        <tr>
                            <td>{{ $tag['id'] }}</td>
                            <td>{{ $tag['name'] }}</td>
                            <td>{{ $tag['type'] }}</td>
                            <td>{{ $tag['created_at'] }}</td>
                            <td>
                                <a href="{{ route('home', ['id' => $tag['id']]) }}" class="btn btn-info btn-xs"><i class="fa fa-eye" title="View"></i> </a>
                                <a href="{{ route('tags.edit', ['id' => $tag['id']]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="Edit"></i> </a>
                                <a href="{{ route('tags.show', ['id' => $tag['id']]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="Delete"></i> </a>
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
