@extends('layouts.admin')
@section('content')
<style>
#DataTables_Table_0_filter{
	display:none;
}
a.buttons-select-all, a.buttons-select-none{
	display:none;
}
@if($expense_id == "")
	a.buttons-excel, a.buttons-pdf{
		display:none;
	}
@endif
</style>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.inventory.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">			
			@if($expense_id == "")
				<p id="">
					<span id="" class="">Expense: </span><input class="" type="text" id="invoice_id" />				
				</p>
				<table class=" table table-bordered table-striped table-hover datatable datatable-Inventory">
					<thead>
						<tr>
							<th></th>
							<th>
								Invoice Number
							</th>
							<th>
								Expense Total
							</th>
							<th>
								Expense Paid
							</th>
							<th>
								Expense Pending
							</th>
							<th>
								Payment Status
							</th>
							<th>
								Supplier Name
							</th>
							<th>
								Supplier Number
							</th>
							<th>
								Supplier Email
							</th>
							<th>
								Payment Detail
							</th>                        
						</tr>
					</thead>
					<tbody>
						@foreach($payment_arr as $key => $payment)
							<tr data-entry-id="{{ $key }}">
								<td>
								   
								</td>
								<td>
									{{ $payment['invoice_number'] ?? '' }}
								</td>
								<td>
									{{ $payment['expense_total'] ?? '' }}
								</td>
								<td>
									{{ $payment['expense_paid'] ?? '' }}
								</td>
								<td>
									{{ $payment['expense_pending'] ?? '' }}
								</td>
								<td>
									{{ $payment['payment_status'] ?? '' }}
								</td>
								<td>
									{{ $payment['supplier_name'] ?? '' }}
								</td>
								<td>
									{{ $payment['supplier_number'] ?? '' }}
								</td>
								<td>
									{{ $payment['supplier_email'] ?? '' }}
								</td>
								<td>                                
									<table class=" table table-bordered table-hover">
										<thead>
											<tr>
												<th>
													Amount
												</th>
												<th>
													Description
												</th>
												<th>
													Date
												</th>
												<th>
													Name
												</th>												                       
											</tr>
										</thead>
										<tbody>
										@foreach($payment['payment_detail'] as $payment_detail)
											<tr>
												<td>
													{{ $payment_detail['amount'] ?? '' }}
												</td>
												<td>
													{{ $payment_detail['description'] ?? '' }}
												</td>
												<td>
													{{ $payment_detail['date'] ?? '' }}
												</td>
												<td>
													{{ $payment_detail['name'] ?? '' }}
												</td>
											</tr>
										@endforeach
										</tbody>
									</table>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<div class="mb-2">Invoice Number: <b>{{ $payment_arr[$expense_id]['invoice_number'] ?? '' }}</b></div>
				<table class=" table table-bordered table-striped table-hover datatable datatable-Inventory">
					<thead>
						<tr>
							<th></th>
							<th>
								Amount
							</th>
							<th>
								Description
							</th>
							<th>
								Date
							</th>
							<th>
								Name
							</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($payment_arr[$expense_id]))
							@foreach($payment_arr[$expense_id]['payment_detail'] as $payment_detail)
								<tr>
									<td>
									   
									</td>
									<td>
										{{ $payment_detail['amount'] ?? '' }}
									</td>
									<td>
										{{ $payment_detail['description'] ?? '' }}
									</td>
									<td>
										{{ $payment_detail['date'] ?? '' }}
									</td>
									<td>
										{{ $payment_detail['name'] ?? '' }}
									</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			@endif
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)  
 

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

  $("#invoice_id").keyup(function() {
    invoiceFilter = $(this).val();
	
    oTable.draw();
  });
})

// invoice filter
invoiceFilter = "";

$.fn.dataTableExt.afnFiltering.push(
  function(oSettings, aData, iDataIndex) {
	
    if (typeof aData.finvoice == 'undefined') {
      aData.finvoice = aData[1];
    }

    if (invoiceFilter && !isNaN(invoiceFilter)) {     
	  if (aData.finvoice != invoiceFilter) {
        return false;
      }
    }

    return true;
  }
);

</script>
@endsection