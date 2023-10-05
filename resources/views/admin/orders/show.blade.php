@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.order.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.id') }}
                        </th>
                        <td>
                            {{ $order->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.sales_manager') }}
                        </th>
                        <td>
                            {{ $order->sales_manager->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.customer') }}
                        </th>
                        <td>
                            {{ $order->customer->name ?? '' }}
                        </td>
                    </tr>
					<tr>
						<th>
                            Order Items
                        </th>
                        <td>
                            <table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<th>
											Product Name
										</th>
										<th>
											Price
										</th>
										<th>
											Stock
										</th>
										<th>
											Ordered Quantity
										</th>
										<th>
											Amount
										</th>
									</tr>
									@foreach($order->	order_item as $item)
										<tr>
											<td>
											{{ $item->name }}
											</td>
											<td>
											{{ $item->selling_price }}
											</td>
											<td>
											{{ $item->stock }}
											</td>
											<td>
											{{ $item->quantity }}
											</td>
											<td>
											{{ $item->quantity * $item->selling_price }}
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
                        </td>
					</tr>
					<tr>
                        <th>
                            {{ trans('cruds.order.fields.extra_discount') }}
                        </th>
                        <td>
                            {{ $order->extra_discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.order_total') }}
                        </th>
                        <td>
                            {{ $order->order_total }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.comments') }}
                        </th>
                        <td>
                            {{ $order->comments }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.delivery_note') }}
                        </th>
                        <td>
                            {{ $order->delivery_note }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.customer_sign') }}
                        </th>
                        <td>
                            {{ $order->customer_sign }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Order::STATUS_SELECT[$order->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection