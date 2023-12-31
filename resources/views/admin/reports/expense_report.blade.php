@extends('layouts.admin')
@section('content')
    <style>
        .buttons-select-all,
        .buttons-select-none,
        .btn-danger,
        #DataTables_Table_0_filter {
            display: none;
        }
    </style>
    <div class="card">
        <div class="card-header">
            {{ trans('reports.purchase_report.title') }}
        </div>

        <div class="card-body">
            <div>
                <div class="row">
                    <div class="col-md-5">
                        <div id="date_filter" class="row ">
                            <div class="col-lg-auto mb-3"><span id="date-label-from" class="date-label">From: </span><input
                                    class="date_range_filter" type="text" id="datepicker_from" /></div>
                            <div class="col-lg-auto mb-3"> <span id="date-label-to" class="date-label">To: </span><input
                                    class="date_range_filter" type="text" id="datepicker_to" /></div>
                        </div>

                    </div>
                    <div class="col-md-2">
                        <select id="supplier">
                            <option value="">-- Select Supplier --</option>
                            @if (!empty($suppliers))
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="5">
                        <span class="cb_conatiner">
                            <span class="paid"><input type="checkbox" id="paid" name="paid" value="1" />
                                Paid</span>
                            <span class="unpaid"><input type="checkbox" id="unpaid" name="unpaid" value="0" /> Un
                                Paid</span>
                            <span class="overdue"><input type="checkbox" id="overdue" name="overdue" value="2" />
                                Over Due</span>
                        </span>
                    </div>
                </div>
                <table class=" table table-bordered table-striped table-hover datatable datatable-Tax">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('reports.purchase_report.date') }}
                            </th>
                            <th>
                                {{ trans('reports.purchase_report.type') }}
                            </th>
                            <th>
                                {{ trans('reports.purchase_report.no') }}
                            </th>
                            <th>
                                {{ trans('reports.purchase_report.supplier') }}
                            </th>
                            <th class="d-none">
                                Supplier ID
                            </th>
                            <th>
                                {{ trans('reports.purchase_report.amount') }}
                            </th>
                            <th>
                                {{ trans('reports.purchase_report.status') }}
                            </th>
                            <th class="d-none">
                                Status ID
                            </th>
                            <th>
                                {{ trans('reports.purchase_report.action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventories as $key => $inventory)
                            @php
                                if (!empty($inventory->payment)) {
                                    $bgcolor = '#ffab00';
                                    if ($inventory->payment->expense_pending == 0 && $inventory->payment->payment_status == 1) {
                                        $bgcolor = '#75dc75';
                                    } else {
                                        if (strtotime($inventory->due_date) < strtotime(date('Y-m-d H:i:s'))) {
                                            if (!empty($inventory->due_date)) {
                                                $bgcolor = '#ff5050';
                                            }
                                        }
                                    }
                                }
                            @endphp

                            <tr style="background-color: {{ $bgcolor }}" data-entry-id="{{ $inventory->id }} ">
                                <td>

                                </td>
                                <td>
                                    {{ date('d/m/Y', strtotime($inventory->created_at)) }}
                                </td>
                                <td>
                                    Expense
                                </td>
                                <td>
                                    {{ $inventory->invoice_number }}
                                </td>
                                <td>
                                    {{ $inventory->supplier->supplier_name ?? '' }}
                                </td>
                                <td class="d-none">
                                    {{ $inventory->supplier->id ?? '' }}
                                </td>
                                <td>
                                    {{ $inventory->final_price }}
                                </td>
                                <td>
                                    @if (!empty($inventory->payment))
                                        @if ($inventory->payment->expense_pending == 0 && $inventory->payment->payment_status == 1)
                                            {{ $status[$inventory->payment->payment_status] }}
                                        @else
                                            @if (strtotime($inventory->due_date) < strtotime(date('Y-m-d H:i:s')))
                                                @if (!empty($inventory->due_date))
                                                    {{ $status[2] }}
                                                    {{ round((strtotime(date('Y-m-d')) - strtotime($inventory->due_date)) / (60 * 60 * 24)) + 1 }}
                                                    days
                                                @endif
                                            @else
                                                @if (date('Y-m-d', strtotime($inventory->due_date)) == date('Y-m-d', strtotime(date('Y-m-d H:i:s'))))
                                                    {{ $status[0] }} today
                                                @else
                                                    {{ $status[0] }}
                                                    {{ round((strtotime($inventory->due_date) - strtotime(date('Y-m-d'))) / (60 * 60 * 24)) - 1 }}
                                                    days
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                </td>
                                <td class="d-none">
                                    @if (!empty($inventory->payment))
                                        @php($status_val = 0)
                                        @if ($inventory->payment->expense_pending == 0 && $inventory->payment->payment_status == 1)
                                            @php($status_val = 1)
                                        @else
                                            @if (strtotime($inventory->due_date) < strtotime(date('Y-m-d H:i:s')))
                                                @if (!empty($inventory->due_date))
                                                    @php($status_val = 2)
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                    {{ $status_val }}
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-primary"
                                        href="{{ route('admin.inventories.show', $inventory->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                    <a class="btn btn-xs btn-info"
                                        href="{{ route('admin.inventories.edit', $inventory->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
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
            @can('tax_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.taxes.massDestroy') }}",
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
            //let table = $('.datatable-Tax:not(.ajaxTable)').DataTable({ buttons: dtButtons })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            var oTable = $('.datatable-Tax:not(.ajaxTable)').DataTable({
                "oLanguage": {
                    "sSearch": "Filter Data"
                },
                "iDisplayLength": -1,
                "sPaginationType": "full_numbers",
                buttons: dtButtons,

            });
            var urlParams = new URLSearchParams(window.location.search); //get all parameters
            var status = urlParams.get('status');
            if (status) {
                if (status == 1) {
                    $('#paid').prop("checked", true).on("change", function() {
                        paidFilter = "1"
                    }).trigger("change");
                } else if (status == 0) {
                    $('#unpaid').prop("checked", true).on("change", function() {
                        unpaidFilter = "0"
                    }).trigger("change");
                } else {
                    $('#overdue').prop("checked", true).on("change", function() {
                        overdueFilter = "2"
                    }).trigger("change");
                }
            }

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

            $("#supplier").change(function() {
                supplierFilter = $(this).val();
                oTable.draw();
            });

            $('#paid').change(function() {
                if (this.checked) {
                    paidFilter = "1"
                } else {
                    paidFilter = "";
                }
                oTable.draw();
            });

            $('#unpaid').change(function() {
                if (this.checked) {
                    unpaidFilter = "0"
                } else {
                    unpaidFilter = "";
                }
                oTable.draw();
            });

            $('#overdue').change(function() {
                if (this.checked) {
                    overdueFilter = "2"
                } else {
                    overdueFilter = "";
                }
                oTable.draw();
            });

        });

        // Date range filter
        minDateFilter = "";
        maxDateFilter = "";
        supplierFilter = "";
        paidFilter = "";
        unpaidFilter = "";
        overdueFilter = "";

        $.fn.dataTableExt.afnFiltering.push(
            function(oSettings, aData, iDataIndex) {
                if (typeof aData._date == 'undefined') {
                    var date_piece = aData[1].split("/");
                    aData._date = new Date(date_piece[2] + "-" + date_piece[1] + "-" + date_piece[0]).getTime();
                }

                if (typeof aData._supplier == 'undefined') {
                    aData._supplier = aData[5];
                }

                if (typeof aData._pay_status == 'undefined') {
                    aData._pay_status = aData[8];
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

                if (supplierFilter && !isNaN(supplierFilter)) {
                    if (aData._supplier != supplierFilter) {
                        return false;
                    }
                }

                if (paidFilter && !isNaN(paidFilter)) {
                    if (aData._pay_status != paidFilter) {
                        return false;
                    }
                }

                if (unpaidFilter && !isNaN(unpaidFilter)) {
                    if (aData._pay_status != unpaidFilter) {
                        return false;
                    }
                }

                if (overdueFilter && !isNaN(overdueFilter)) {
                    if (aData._pay_status != overdueFilter) {
                        return false;
                    }
                }

                return true;
            }
        );
    </script>
@endsection
