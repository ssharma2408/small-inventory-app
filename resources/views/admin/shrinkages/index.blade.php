@extends('layouts.admin')
@section('content')
    @can('shrinkage_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.shrinkages.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.shrinkage.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.shrinkage.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div>
                <div id="date_filter" class="row ">
                    <div class="col-lg-auto mb-3"><span id="date-label-from" class="date-label">From: </span><input
                            class="date_range_filter" type="text" id="datepicker_from" /></div>
                    <div class="col-lg-auto mb-3"> <span id="date-label-to" class="date-label">To: </span><input
                            class="date_range_filter" type="text" id="datepicker_to" /></div>
                </div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-Shrinkage">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.shrinkage.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.shrinkage.fields.product') }}
                            </th>
                            <th>
                                {{ trans('cruds.shrinkage.fields.number') }}
                            </th>
                            <th>
                                {{ trans('cruds.shrinkage.fields.date') }}
                            </th>
                            <th>
                                {{ trans('cruds.shrinkage.fields.added_by') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shrinkages as $key => $shrinkage)
                            <tr data-entry-id="{{ $shrinkage->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $shrinkage->id ?? '' }}
                                </td>
                                <td>
                                    {{ $shrinkage->product->name ?? '' }}
                                </td>
                                <td>
                                    {{ $shrinkage->number ?? '' }}
                                </td>
                                <td>
                                    {{ $shrinkage->date ?? '' }}
                                </td>
                                <td>
                                    {{ $shrinkage->added_by->name ?? '' }}
                                </td>
                                <td>
                                    @can('shrinkage_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.shrinkages.show', $shrinkage->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('shrinkage_edit')
                                        <a class="btn btn-xs btn-info"
                                            href="{{ route('admin.shrinkages.edit', $shrinkage->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('shrinkage_delete')
                                        <form action="{{ route('admin.shrinkages.destroy', $shrinkage->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger"
                                                value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('shrinkage_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.shrinkages.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            //let table = $('.datatable-Shrinkage:not(.ajaxTable)').DataTable({ buttons: dtButtons })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            var oTable = $('.datatable-Shrinkage:not(.ajaxTable)').DataTable({
                "oLanguage": {
                    "sSearch": "Filter Data"
                },
                "iDisplayLength": -1,
                "sPaginationType": "full_numbers",
                buttons: [],

            });

            $("#datepicker_from").datepicker({
                showOn: "button",
                //buttonImage: "images/calendar.gif",
                buttonImageOnly: false,
                "onSelect": function(date) {
                    minDateFilter = new Date(date).getTime();
                    oTable.draw();
                }
            }).keyup(function() {
                minDateFilter = new Date(this.value).getTime();
                oTable.draw();
            });

            $("#datepicker_to").datepicker({
                showOn: "button",
                //buttonImage: "images/calendar.gif",
                buttonImageOnly: false,
                "onSelect": function(date) {
                    maxDateFilter = new Date(date).getTime();
                    oTable.draw();
                }
            }).keyup(function() {
                maxDateFilter = new Date(this.value).getTime();
                oTable.draw();
            });

        })

        // Date range filter
        minDateFilter = "";
        maxDateFilter = "";

        $.fn.dataTableExt.afnFiltering.push(
            function(oSettings, aData, iDataIndex) {

                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }

                if (minDateFilter && !isNaN(minDateFilter)) {
                    if (aData._date < minDateFilter) {
                        return false;
                    }
                }

                if (maxDateFilter && !isNaN(maxDateFilter)) {
                    if (aData._date > maxDateFilter) {
                        return false;
                    }
                }

                return true;
            }
        );
    </script>
@endsection
