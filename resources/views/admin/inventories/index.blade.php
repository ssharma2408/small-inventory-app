@extends('layouts.admin')
@section('content')
@can('inventory_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.inventories.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.inventory.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.inventory.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <p id="date_filter">
				<span id="date-label-from" class="date-label">From: </span><input class="date_range_filter" type="text" id="datepicker_from" />
				<span id="date-label-to" class="date-label">To:<input class="date_range_filter" type="text" id="datepicker_to" />
			</p>
			<table class=" table table-bordered table-striped table-hover datatable datatable-Inventory">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.inventory.fields.id') }}
                        </th>
						 <th>
                            {{ trans('cruds.inventory.fields.product') }}
                        </th>
						<th>
                            {{ trans('cruds.category.fields.category') }}
                        </th>
						<th>
                            {{ trans('cruds.category.fields.sub_category') }}
                        </th>
						<th>
                            {{ trans('cruds.inventory.fields.box_or_unit') }}
                        </th>
						<th>
                            {{ trans('cruds.inventory.fields.stock') }}
                        </th>
						 <th>
                            {{ trans('cruds.inventory.fields.purchase_price') }}
                        </th>
						<th>
                            {{ trans('cruds.inventory.fields.tax') }}
                        </th>
						<th>
                            {{ trans('cruds.inventory.fields.final_price') }}
                        </th>
						<th>
                            {{ trans('cruds.inventory.fields.invoice_number') }}
                        </th>
						<th>
                            Date
                        </th>						
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventories as $key => $inventory)
                        <tr data-entry-id="{{ $inventory->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $inventory->id ?? '' }}
                            </td>
							 <td>
                                {{ $inventory->product->name ?? '' }}
                            </td>
							<td>
                                {{ $inventory->category->name ?? '' }}
                            </td>
							<td>
                                {{ $inventory->sub_category->name ?? '' }}
                            </td>
							<td>
                                {{ App\Models\Inventory::BOX_OR_UNIT_RADIO[$inventory->box_or_unit] ?? '' }}
                            </td>
							<td>
                                {{ $inventory->stock ?? '' }}
                            </td>
							<td>
                                {{ $inventory->purchase_price ?? '' }}
                            </td>
							<td>
                                {{ $inventory->tax->title ?? '' }}
                            </td>
							<td>
                                {{ $inventory->final_price ?? '' }}
                            </td>
							<td>
                                {{ $inventory->invoice_number ?? '' }}
                            </td>
							<td>
                                {{ $inventory->created_at ?? '' }}
                            </td>							
                            <td>
                                @can('inventory_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.inventories.show', $inventory->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('inventory_edit')
                                    @if(!in_array($inventory->id, $expense_id_arr))
									<a class="btn btn-xs btn-info" href="{{ route('admin.inventories.edit', $inventory->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
									@endif
                                @endcan

                                @can('inventory_delete')
                                    <form action="{{ route('admin.inventories.destroy', $inventory->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
								
								@can('inventory_show')
                                    <a class="btn btn-xs btn-primary" href="/admin/inventories/payment/{{$inventory->id}}">
                                        View Payment
                                    </a>
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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('inventory_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.inventories.massDestroy') }}",
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
  //let table = $('.datatable-Inventory:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
    var oTable = $('.datatable-Inventory:not(.ajaxTable)').DataTable({
    "oLanguage": {
      "sSearch": "Filter Data"
    },
    "iDisplayLength": -1,
    "sPaginationType": "full_numbers",
	buttons: dtButtons,

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
      aData._date = new Date(aData[11]).getTime();
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