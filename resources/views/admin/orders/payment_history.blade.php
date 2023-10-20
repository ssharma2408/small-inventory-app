@extends('layouts.admin')
@section('content')
<style>
#DataTables_Table_0_filter{
	display:none;
}
a.buttons-select-all, a.buttons-select-none{
	display:none;
}
</style>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.order.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <p id="">
				<span id="" class="">Order ID: </span><input class="" type="text" id="order_id" />				
			</p>
			<table class=" table table-bordered table-striped table-hover datatable datatable-Order">
                <thead>
                    <tr>
						<th></th>
                        <th>
                            Order Number
                        </th>
                        <th>
                            Order Total
                        </th>
						<th>
                            Order Paid
                        </th>
                        <th>
                            Order Pending
                        </th>
						<th>
                            Payment Status
                        </th>
                        <th>
                            Customer Name
                        </th>
                        <th>
                            Customer Phone Number
                        </th>
                        <th>
                            Customer Email
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
                                {{ $payment['order_number'] ?? '' }}
                            </td>
                            <td>
                                {{ $payment['order_total'] ?? '' }}
                            </td>
							<td>
                                {{ $payment['order_paid'] ?? '' }}
                            </td>
                            <td>
                                {{ $payment['order_pending'] ?? '' }}
                            </td>
							<td>
                                {{ $payment['payment_status'] ?? '' }}
                            </td>
                            <td>
                                {{ $payment['cust_name'] ?? '' }}
                            </td>
                            <td>
                                {{ $payment['phone_number'] ?? '' }}
                            </td>
                            <td>
                                {{ $payment['email'] ?? '' }}
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
  
    var oTable = $('.datatable-Order:not(.ajaxTable)').DataTable({
    "oLanguage": {
      "sSearch": "Filter Data"
    },
    "iDisplayLength": -1,
    "sPaginationType": "full_numbers",
	buttons: dtButtons,

  });

  $("#order_id").keyup(function() {
    orderFilter = $(this).val();
	
    oTable.draw();
  });
})

// invoice filter
orderFilter = "";

$.fn.dataTableExt.afnFiltering.push(
  function(oSettings, aData, iDataIndex) {
	
    if (typeof aData.finvoice == 'undefined') {
      aData.finvoice = aData[1];
    }

    if (orderFilter && !isNaN(orderFilter)) {     
	  if (aData.finvoice != orderFilter) {
        return false;
      }
    }

    return true;
  }
);

</script>
@endsection