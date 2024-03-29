@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.order.title') }}
    </div>

    <div class="card-body">
		<form method="POST" id="frmcomp" action="{{ route("admin.orders.complete") }}" enctype="multipart/form-data">
			@csrf
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
								{{ $order->customer->company_name ?? '' }}
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
												In Stock
											</th>
											<th>
												{{ trans('cruds.order.fields.min_usp') }}
											</th>
											<th>
												{{ trans('cruds.order.fields.max_usp') }}
											</th>											
											<th>
												Box or unit
											</th>
											<th>
												Ordered Quantity
											</th>
											<th>
												Sales Price
											</th>
											<th>
												Tax
											</th>
											<th>
												Comment
											</th>
											<th>
												Amount
											</th>
										</tr>
										@foreach($order->order_item as $item)
											<tr>
												<td>
												{{ $item->name }}
												</td>
												<td>
												{{ $item->stock }}
												</td>
												<td>
												{{ $item->selling_price }}
												</td>
												<td>
												{{ $item->maximum_selling_price }}
												</td>												
												<td>
													@if($item->is_box)
														Box
													@else
														Unit
													@endif
												</td>
												<td>
												{{ $item->quantity }}
												</td>
												<td>
												{{ $item->sale_price }}
												</td>
												<td>
												{{ $item->title }}
												</td>
												<td>
												{{ $item->comment }}
												</td>
												<td>
													@php
														$qty = $item->quantity;
														
														$amount = $qty * $item->sale_price;
														
														$total = $amount + (($amount * $item->tax)/100);
													@endphp
													{{ sprintf('%0.2f', $total) }}
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</td>
						</tr>						
						<tr>
							<th>
								{{ trans('cruds.order.fields.order_total_without_tax') }}
							</th>
							<td>
								{{ $order->order_total_without_tax }}
							</td>
						</tr>
						<tr>
							<th>
								{{ trans('cruds.order.fields.order_tax') }}
							</th>
							<td>
								{{ $order->order_tax }}
							</td>
						</tr>
						<tr>
							<th>
								{{ trans('cruds.order.fields.discount_type') }}
							</th>
							<td>
								{{ App\Models\Order::DISCOUNT_TYPE_RADIO[$order->discount_type] ?? '' }}
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
						@if($credit_balance > 0)
							<tr>
								<th>
									{{ trans('cruds.order.fields.credit_balance') }}
								</th>
								<td>
									{{ $credit_balance }}
								</td>
							</tr>
						@endif
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
								{{ trans('cruds.order.fields.status') }}
							</th>
							<td>
								{{ App\Models\Order::STATUS_SELECT[$order->status] ?? '' }}
							</td>
						</tr>
						<tr>
							<th>
								{{ trans('cruds.order.fields.order_date') }}
							</th>
							<td>
								{{ $order->order_date }}
							</td>
						</tr>
					</tbody>
				</table>				
				@if($order->status == 4 && (($role['title'] == 'Delivery Agent' && $order->delivery_agent_id == \Auth::user()->id) || $role['title'] == 'Admin' || $role['title'] == 'Website Admin'))
					<div class="form-group">
						<label for="delivery_pic">{{ trans('cruds.order.fields.delivery_pic') }}</label>
						<input class="form-control" type="file" name="delivery_pic" id="delivery_pic" />
						@if ($errors->has('delivery_pic'))
							<span class="text-danger">{{ $errors->first('delivery_pic') }}</span>
						@endif
						<span class="help-block">{{ trans('cruds.order.fields.delivery_pic_helper') }}</span>
					</div>	
					<div class="m-signature-pad mb-4" id="signature-pad">
						<div class="m-signature-pad--body">
							<canvas id="canvas" width="611" height="150"></canvas>						
						</div>
						<span class="text-danger">Signature is required</span>
						<div class="m-signature-pad--footer">
							<div class="description">
								Sign above
							</div>
							<button class="btn btn-default" id="clear-signature" type="button">Clear</button>
							<button class="btn btn-danger" type="submit">
								{{ trans('global.save') }}
							</button>
							<input data-rule-signature="true" id="signature" name="signature" type="hidden">
							<input data-rule-signature="true" name="id" type="hidden" value="{{ $order->id }}">
						</div>
					</div>
				@endif
				@if($order->status == 1 && (($role['title'] == 'Delivery Agent' && $order->delivery_agent_id == \Auth::user()->id) || $role['title'] == 'Admin' || $role['title'] == 'Website Admin'))
					<div class="form-group">
						<label for="">
							{{ trans('cruds.order.fields.delivery_pic') }}
						</label>
							<img width = "100" height="100" src="{{ $_ENV['DO_CDN_URL'].$order->delivery_pic }}" />
						
					</div>
					<div class="form-group">
						<label for="">
							Customer Sign
						</label>							
							{{ $order->customer_sign }}						
					</div>
				@endif
				<div class="form-group">					
					<a class="btn btn-default" href="{{ route('admin.orders.index') }}">
						{{ trans('global.back_to_list') }}
					</a>
				</div>
			</div>
		</form>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/signature_pad.min.js') }}"></script>

<script>	
	$(".text-danger").hide();
	var canvas = document.getElementById("canvas");
	var signaturePad = new SignaturePad(canvas);
	
	$('#clear-signature').on('click', function(){
		signaturePad.clear();
	});		
		
	  $( "#frmcomp" ).submit(function( event ) {
		
		var sigData = canvas.toDataURL("image/png");
		$('#signature').attr('value', sigData);
		
		if( signaturePad.isEmpty()){
			$(".text-danger").show();
			return false;            
        }
		
	  });	
	
	
</script>
@endsection
