@extends('layouts.admin')
@section('content')
<style>
	.buttons-select-all, .buttons-select-none, .btn-danger, #DataTables_Table_0_filter{
		display:none;
	}
</style>
<div class="card">
    <div class="card-header">
        {{ trans('reports.purchase_report.title') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
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
						<th>
                            {{ trans('reports.purchase_report.memo') }}
                        </th>
						<th>
                            {{ trans('reports.purchase_report.amount') }}
                        </th>
						<th>
                            {{ trans('reports.purchase_report.status') }}
                        </th>
                        <th>
                            {{ trans('reports.purchase_report.action') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
					@foreach($inventories as $key => $inventory)
                        <tr data-entry-id="{{ $inventory->id }}">
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
								{{ $inventory->supplier->supplier_name }}
                            </td>
							<td>
								
                            </td>
							<td>
								{{ $inventory->final_price }}
                            </td>
							<td>
								@if($inventory->payment->expense_pending == 0 && $inventory->payment->payment_status == 1)
									{{ $status[$inventory->payment->payment_status] }}
								@else
									@if(strtotime($inventory->due_date) < strtotime(date('Y-m-d H:i:s')))
										@if(!empty($inventory->due_date))
											{{ $status[2] }} {{round((strtotime(date('Y-m-d'))- strtotime($inventory->due_date)) / (60 * 60 * 24))}} days
										@endif
									@else
										@if(date('Y-m-d', strtotime($inventory->due_date)) == date('Y-m-d', strtotime(date('Y-m-d H:i:s'))))
											{{ $status[0] }} today
										@else
											{{ $status[0] }} {{round((strtotime($inventory->due_date)- strtotime(date('Y-m-d'))) / (60 * 60 * 24))}} days
										@endif
									@endif
								@endif
                            </td>
							<td>
								<a class="btn btn-xs btn-primary" href="{{ route('admin.inventories.show', $inventory->id) }}">
									{{ trans('global.view') }}
								</a>
								<a class="btn btn-xs btn-info" href="{{ route('admin.inventories.edit', $inventory->id) }}">
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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('tax_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.taxes.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Tax:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection