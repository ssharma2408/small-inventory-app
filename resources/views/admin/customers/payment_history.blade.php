@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.customer.title') }}
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
						$total = $total + $payment->order_total;
						$total_paid = $total_paid + $payment->order_paid;
						$total_pending = $total_pending + $payment->order_pending;
					@endphp
					<tr>
                       <td>
							{{ $payment->order_number ?? '' }}
					   </td>
						<td>
							{{ $payment->order_total ?? '' }}
					   </td>
						<td>
							{{ $payment->order_paid ?? '' }}
					   </td>
						<td>
							{{ $payment->order_pending ?? '' }}
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
                <a class="btn btn-default" href="{{ route('admin.customers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection