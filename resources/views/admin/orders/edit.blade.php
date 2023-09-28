@extends('layouts.admin')
@section('content')

<?php
	$ddl_html = "No Product Found";
	if(!empty($products)){
		$ddl_html = '<select class="form-control select2 order_item" name="item_name[]" required>';
			$ddl_html .= '<option value="">-- Select Product --</option>';
			foreach($products as $product){
				$ddl_html .= '<option value="'.$product['id'].'">'.$product['product_name'].'</option>';
			}
		$ddl_html .= '</select>';
	}
?>

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.order.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.orders.update", [$order->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="sales_manager_id">{{ trans('cruds.order.fields.sales_manager') }}</label>
                <select class="form-control select2 {{ $errors->has('sales_manager') ? 'is-invalid' : '' }}" name="sales_manager_id" id="sales_manager_id" required>
                    @foreach($sales_managers as $id => $entry)
                        <option value="{{ $id }}" {{ (old('sales_manager_id') ? old('sales_manager_id') : $order->sales_manager->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('sales_manager'))
                    <span class="text-danger">{{ $errors->first('sales_manager') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.sales_manager_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="customer_id">{{ trans('cruds.order.fields.customer') }}</label>
                <select class="form-control select2 {{ $errors->has('customer') ? 'is-invalid' : '' }}" name="customer_id" id="customer_id" required>
                    @foreach($customers as $id => $entry)
                        <option value="{{ $id }}" {{ (old('customer_id') ? old('customer_id') : $order->customer->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('customer'))
                    <span class="text-danger">{{ $errors->first('customer') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.customer_helper') }}</span>
            </div>
			
			<div class="form-group">
                <label class="required" for="order_items">Order Items</label>
                <div class="row">
					<div class="col-md-3">
						<b>Product Name</b>
					</div>
					<div class="col-md-2">
						<b>Stock</b>
					</div>
					<div class="col-md-2">
						<b>Price</b>
					</div>
					<div class="col-md-2">
						<b>Quantity</b>
					</div>
					<div class="col-md-2">
						<b>Amount</b>
					</div>
					<div class="col-md-1">
						
					</div>
				</div>
				<?php
					if(!empty($order_items)){
						echo '<div class="item_container">';
						$cnt = 0;
						foreach($order_items as $order_item){
							echo '<div class="row mb-3">
									<div class="col-md-3">';
									
									echo '<select class="form-control select2 order_item" name="item_name[]" required>';
										echo '<option value="">-- Select Product --</option>';										
										foreach($products as $product){
											$selected = "";
											if($product['id'] == $order_item->product_id){
												$selected = "selected";
											}
											echo '<option value="'.$product['id'].'" '.$selected.'>'.$product['product_name'].'</option>';
										}
									echo '</select>';
										 															
									echo '</div>
									<div class="col-md-2">
										<input class="form-control" type="number" name="item_stock[]" disabled value="'.$order_item->stock.'" />
									</div>
									<div class="col-md-2">
										<input class="form-control" type="text" name="item_price[]" disabled value="'.$order_item->price.'" />
									</div>
									<div class="col-md-2">
										<input class="form-control quantity" type="number" name="item_quantity[]" min="1" value="'.$order_item->quantity.'"/>
									</div>
									<div class="col-md-2">
										<input class="form-control amount" type="text" name="item_amount[]" disabled value="'.$order_item->price * $order_item->quantity.'" />
									</div>
									<div class="col-md-1">';
										if(!$cnt){
											echo '<span class="add_row" id="add_row" data-key ="">+</span>';
										}else{
											echo '<span class="remove_row" id="remove_row">-</span>';
										}
										
									echo '</div>
								</div>';
								$cnt++;
						}
						
						echo '</div>';
					}
				?>
				
            </div>
			
            <div class="form-group">
                <label class="required" for="order_total">{{ trans('cruds.order.fields.order_total') }}</label>
                <input class="form-control {{ $errors->has('order_total') ? 'is-invalid' : '' }}" type="number" name="order_total" id="order_total" value="{{ old('order_total', $order->order_total) }}" step="0.01" required>
                @if($errors->has('order_total'))
                    <span class="text-danger">{{ $errors->first('order_total') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.order_total_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="comments">{{ trans('cruds.order.fields.comments') }}</label>
                <textarea class="form-control {{ $errors->has('comments') ? 'is-invalid' : '' }}" name="comments" id="comments">{{ old('comments', $order->comments) }}</textarea>
                @if($errors->has('comments'))
                    <span class="text-danger">{{ $errors->first('comments') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.comments_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="delivery_note">{{ trans('cruds.order.fields.delivery_note') }}</label>
                <textarea class="form-control {{ $errors->has('delivery_note') ? 'is-invalid' : '' }}" name="delivery_note" id="delivery_note">{{ old('delivery_note', $order->delivery_note) }}</textarea>
                @if($errors->has('delivery_note'))
                    <span class="text-danger">{{ $errors->first('delivery_note') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.delivery_note_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="customer_sign">{{ trans('cruds.order.fields.customer_sign') }}</label>
                <textarea class="form-control {{ $errors->has('customer_sign') ? 'is-invalid' : '' }}" name="customer_sign" id="customer_sign">{{ old('customer_sign', $order->customer_sign) }}</textarea>
                @if($errors->has('customer_sign'))
                    <span class="text-danger">{{ $errors->first('customer_sign') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.customer_sign_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.order.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Order::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $order->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
	<script>	
		$(".add_row").click(function(){
			$(this).parent().parent().parent().append(row_html());
		});
		$(".item_container").on('click','.remove_row',function(){
		   $(this).parent().parent().remove();
		   calculate_total();
		});
		
		function row_html(){
			return '<div class="row mb-3"><div class="col-md-3"><?php echo $ddl_html; ?></div><div class="col-md-2"><input class="form-control" type="number" name="item_stock[]" disabled /></div><div class="col-md-2"><input class="form-control" type="text" name="item_price[]" disabled /></div><div class="col-md-2"><input class="form-control quantity" type="number" name="item_quantity[]" min="1" /></div><div class="col-md-2"><input class="form-control amount" type="text" name="item_amount[]" disabled /></div><div class="col-md-1"><span class="remove_row" id="remove_row">-</span></div></div>';	
		}
		
		$(document).on("change", ".order_item", function () {			
			var stock = $(this).parent().next().find('input');
			var qty = $(this).parent().next().next().find('input');
			$.ajax({
                    url: 'get_product_detail/'+$(this).val(),
                    type: 'GET',
					success: function(data) {
						if (data.success) {
							stock.val(data.product.stock);
							qty.val(data.product.price);							
						}
					}
				 });
					
		});
		
		$(document).on("keyup", ".quantity", function () {
			var price = $(this).parent().prev().find('input').val();
			$(this).parent().next().find('input').val($(this).val() * price);
			calculate_total();
			
		});
		
		function calculate_total(){
			var order_total = 0;
			
			$(".amount").each(function() {
			
				order_total += parseFloat($(this).val());
			});
			
			$("#order_total").val(order_total);
		}
	</script>
@endsection