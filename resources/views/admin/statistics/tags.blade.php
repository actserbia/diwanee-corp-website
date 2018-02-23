@extends('layouts.admin')

@section('content')
<div>
    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.admin.statistics.tags') </h2>
                    <div class="clearfix"></div>
                </div>


                <div class="x_content">
                    <br />
                    <form id="statistics_form" method="post" action="{{ route('admin.statistics.tags') }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}

                        @include('blocks.statistics')

                        <div class="ln_solid"></div>

                        @include('blocks.search', ['field' => 'tags*created_at'])

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label') }}">
                                <button type="submit" class="btn btn-success">@lang('blade_templates.admin.statistics.statistics_button_text')</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="x_content">
                    @if (isset($statistics))
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ $model->fieldLabel($statisticName) }}</th>
                                    <th>@lang('blade_templates.admin.statistics.count')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>{{ $model->fieldLabel($statisticName) }}</th>
                                    <th>@lang('blade_templates.admin.statistics.count')</th>
                                </tr>
                            </tfoot>

                            <tbody>
                            @foreach($statistics as $statistic)
                                <tr>
                                    <td>{{ $model->fieldValue($statisticName, $statistic->value) }}</td>
                                    <td>{{ $statistic->count }}</td>
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