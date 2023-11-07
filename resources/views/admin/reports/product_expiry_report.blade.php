@extends('layouts.admin')
@section('content')
<style>
	.buttons-select-all, .buttons-select-none, .btn-danger, #DataTables_Table_0_filter{
		display:none;
	}
</style>
<div class="card">
    <div class="card-header">
        {{ trans('reports.product_expiry_report.title') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <p id="date_filter">
				<span id="date-label-from" class="date-label">From: </span><input class="date_range_filter" type="text" id="datepicker_from" />
				<span id="date-label-to" class="date-label">To:<input class="date_range_filter" type="text" id="datepicker_to" />
				<span id="supplier_span" class="">Product: </span>
				<select id="product">
					<option value="">-- Please Select --</option>
					@if(!empty($products))
						@foreach($products as $product)
							<option value="{{$product->id}}">{{$product->name}}</option>
						@endforeach
					@endif
				</select>
			</p>
			<table class=" table table-bordered table-striped table-hover datatable datatable-Tax">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('reports.product_expiry_report.expiry_date') }}
                        </th>
                        <th>
                            {{ trans('reports.product_expiry_report.invoice_no') }}
                        </th>
                        <th>
                            {{ trans('reports.product_expiry_report.product_name') }}
                        </th>						
						<th class="d-none">
                            Product ID
                        </th>
						<th>
                            {{ trans('reports.product_expiry_report.expire_in') }}
                        </th>
                        <th>
                            {{ trans('reports.product_expiry_report.action') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
					@foreach($expense_items as $key => $expense_item)					
						@php
							$bgcolor = "";
							if(!empty($expense_item->exp_date)){								
								if(strtotime($expense_item->exp_date) < strtotime(date('Y-m-d'))){
									$bgcolor = "#ff5050";
								}else{
									if(round((strtotime($expense_item->exp_date) - strtotime(date('Y-m-d'))) / (60 * 60 * 24)) - 1 < 30){
										if(!empty($expense_item->exp_date)){
											$bgcolor = "#75dc75";
										}
									}
								}
							}
						@endphp
						<tr style="background-color: {{$bgcolor}}">
                            <td>

                            </td>
							<td>
								@if($expense_item->exp_date != "")
									{{ date('d/m/Y', strtotime($expense_item->exp_date)) }}
								@endif
                            </td>
							<td>
								{{ $expense_item->invoice_number ?? '' }}
                            </td>
							<td>
								{{ $expense_item->name }}
                            </td>
							<td class="d-none">
								{{ $expense_item->product_id ?? '' }}
							</td>
							<td>
								@if(strtotime($expense_item->exp_date) < strtotime(date('Y-m-d')))
									@if(!empty($expense_item->exp_date))
										Expired
									@endif
								@else
									Expire in {{round((strtotime($expense_item->exp_date) - strtotime(date('Y-m-d'))) / (60 * 60 * 24)) - 1}} days
								@endif
                            </td>
							<td>
								<a class="btn btn-xs btn-primary" href="{{ route('admin.inventories.show', $expense_item->id) }}">
									{{ trans('global.view') }}
								</a>
								<a class="btn btn-xs btn-info" href="{{ route('admin.inventories.edit', $expense_item->id) }}">
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
  //let table = $('.datatable-Tax:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
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
  
  $("#product").change(function (){
	  productFilter = $(this).val();
	  oTable.draw();
  });
  
});

// Date range filter
minDateFilter = "";
maxDateFilter = "";
productFilter = "";

$.fn.dataTableExt.afnFiltering.push(
  function(oSettings, aData, iDataIndex) {
    if (typeof aData._date == 'undefined') {
		var date_piece = aData[1].split("/");
	  aData._date = new Date(date_piece[2]+"-"+date_piece[1]+"-"+date_piece[0]).getTime();
    }
	
	if (typeof aData._product == 'undefined') {		
	  aData._product = aData[4];
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

	if (productFilter && !isNaN(productFilter)) {
      if (aData._product != productFilter) {
        return false;
      }
    }

    return true;
  }
);

</script>
@endsection