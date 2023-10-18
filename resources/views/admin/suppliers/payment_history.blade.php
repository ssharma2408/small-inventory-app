@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.supplier.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.suppliers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
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
                    </tr>                    
                </thead>
				<tbody>
                    @php
						$total = 0;
						$total_paid = 0;
						$total_pending = 0;
					@endphp
					@foreach($payments as $key => $payment)
					@php
						$total = $total + $payment->expense_total;
						$total_paid = $total_paid + $payment->expense_paid;
						$total_pending = $total_pending + $payment->expense_pending;
					@endphp
					<tr>
                       <td>
							{{ $payment->invoice_number ?? '' }}
					   </td>
						<td>
							{{ $payment->expense_total ?? '' }}
					   </td>
						<td>
							{{ $payment->expense_paid ?? '' }}
					   </td>
						<td>
							{{ $payment->expense_pending ?? '' }}
					   </td>					   
                    </tr>
					@endforeach
					<tr>
                       <td>
							Total
					   </td>
						<td>
							{{ $total ?? '0' }}
					   </td>
						<td>
							{{ $total_paid ?? '0' }}
					   </td>
						<td>
							{{ $total_pending ?? '0' }}
					   </td>					   
                    </tr>
				</tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.suppliers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection