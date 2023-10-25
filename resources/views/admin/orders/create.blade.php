@extends('layouts.admin')
@section('content')

<?php
	$ddl_html = "No Category Found";
	
	if(!empty($categories)){
		$ddl_html = '<select class="category form-control select2" name="item_category[]" required>';
			$ddl_html .= '<option value="" >Select Option</option>';
			foreach($categories as $cat_id => $val){
				$ddl_html .= '<option value="'.$cat_id.'">'.$val.'</option>';
			}
		$ddl_html .= '</select>';
	}

	$tax_ddl_html = "No Tax Found";
	
	if(!empty($taxes)){
		$tax_ddl_html = '<select class="form-control select2 tax_id" name="item_tax_id[]" required>';
		$tax_ddl_html .= '<option value="" >Please Select</option>';
			foreach($taxes as $tax){
				$tax_ddl_html .= '<option value="'.$tax['id'].'">'.$tax['title'].'</option>';
			}
		$tax_ddl_html .= '</select>';
	}	

?>

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.order.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" id="orderfrm" action="{{ route("admin.orders.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="sales_manager_id">{{ trans('cruds.order.fields.sales_manager') }}</label>
                <select class="form-control select2 {{ $errors->has('sales_manager') ? 'is-invalid' : '' }}" name="sales_manager_id" id="sales_manager_id" required>
                    @foreach($sales_managers as $id => $entry)
                        <option value="{{ $id }}" {{ old('sales_manager_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                        <option value="{{ $id }}" {{ old('customer_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
					<div class="col-md-1">
						<b>Category Name</b>
					</div>
					<div class="col-md-1">
						<b>Sub Category Name</b>
					</div>
					<div class="col-md-1">
						<b>Product Name</b>
					</div>
					<div class="col-md-1">
						<b>In Stock</b>
					</div>
					<div class="col-md-1">
						<b>Min Selling Price</b>
					</div>
					<div class="col-md-1">
						<b>Max Selling Price</b>
					</div>
					<div class="col-md-1">
						<b>Box or unit</b>
					</div>
					<div class="col-md-1">
						<b>Quantity</b>
					</div>
					<div class="col-md-1">
						<b>Sales Price</b>
					</div>
					<div class="col-md-1">
						<b>Tax</b>
					</div>
					<div class="col-md-1">
						<b>Amount</b>
					</div>
					<div class="col-md-1">

					</div>
				</div>
				<div class="item_container">
					<div class="row mb-3">
						<div class="cat_container col-md-1">
							<div class="form-group">								
								<?php
									echo $ddl_html;
								?>
							</div>				
						</div>
						<div class="col-md-1">
							<select class="subcat form-control select2" name="item_subcategory[]">
								<option value="">Please select</option>
							</select>
						</div>
						<div class="col-md-1">
							<select class="order_item form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="item_name[]" required>
								<option value="">Please select</option>
							</select>			
						</div>
						<div class="col-md-1">
							<input class="form-control in_stock" type="number" name="item_stock[]" disabled />
						</div>
						<div class="col-md-1">
							<input class="form-control min" type="text" name="item_price[]" disabled />
						</div>
						<div class="col-md-1">
							<input class="form-control max" type="text" name="item_max_price[]" disabled />
						</div>
						<div class="col-md-1">							
							<input class="form-check-input cb ml-0" type="checkbox" name="is_box[]"/>
							<label class="form-check-label ml-3">Is Box</label>
							<input type="hidden" id="package_val" value="" name="package_val" />
						</div>
						<div class="col-md-1">
							<input class="form-control quantity" type="number" name="item_quantity[]" min="1" required />
							<span class="text-danger qty_err"></span>
						</div>
						<div class="col-md-1">
							<input class="form-control sale_price" type="text" name="item_sale_priec[]" required />
							<span class="text-danger sale_price_err"></span>
						</div>
						<div class="col-md-1">
							<?php
								echo $tax_ddl_html;
							?>
							<input type="hidden" class="tax_val" value="" />
						</div>
						<div class="col-md-1">
							<input class="form-control amount" type="text" name="item_amount[]" disabled />
						</div>
						<div class="col-md-1">
							<span class="add_row" id="add_row" data-key ="">+</span>
						</div>
					</div>
				</div>
            </div>

			<div class="form-group">
                <label for="extra_discount">{{ trans('cruds.order.fields.extra_discount') }}</label>
                <input class="form-control {{ $errors->has('extra_discount') ? 'is-invalid' : '' }}" type="number" name="extra_discount" id="extra_discount" value="{{ old('extra_discount', '') }}" step="0.01">
                @if($errors->has('extra_discount'))
                    <span class="text-danger">{{ $errors->first('extra_discount') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.extra_discount_helper') }}</span>
            </div>
			
            <div class="form-group">
                <label class="required" for="order_total">{{ trans('cruds.order.fields.order_total') }}</label>
                <input class="form-control {{ $errors->has('order_total') ? 'is-invalid' : '' }}" type="number" name="order_total" id="order_total" value="{{ old('order_total', '') }}" step="0.01" required>
                @if($errors->has('order_total'))
                    <span class="text-danger">{{ $errors->first('order_total') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.order_total_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="comments">{{ trans('cruds.order.fields.comments') }}</label>
                <textarea class="form-control {{ $errors->has('comments') ? 'is-invalid' : '' }}" name="comments" id="comments">{{ old('comments') }}</textarea>
                @if($errors->has('comments'))
                    <span class="text-danger">{{ $errors->first('comments') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.comments_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="delivery_note">{{ trans('cruds.order.fields.delivery_note') }}</label>
                <textarea class="form-control {{ $errors->has('delivery_note') ? 'is-invalid' : '' }}" name="delivery_note" id="delivery_note">{{ old('delivery_note') }}</textarea>
                @if($errors->has('delivery_note'))
                    <span class="text-danger">{{ $errors->first('delivery_note') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.delivery_note_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="customer_sign">{{ trans('cruds.order.fields.customer_sign') }}</label>
                <textarea class="form-control {{ $errors->has('customer_sign') ? 'is-invalid' : '' }}" name="customer_sign" id="customer_sign">{{ old('customer_sign') }}</textarea>
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
                        <option value="{{ $key }}" {{ old('status', '3') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
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
		
		$(function() {
			$(".text-danger").html("");
		});
		
		$(".add_row").click(function(){
			$(this).parent().parent().parent().append(row_html());
		});
		$(".item_container").on('click','.remove_row',function(){
		   $(this).parent().parent().remove();
		   calculate_total();
		});
		
		function row_html(){
			return '<div class="row mb-3"><div class="cat_container col-md-1"><?php echo $ddl_html;?></div><div class="col-md-1"><select class="subcat form-control select2" name="item_subcategory[]"><option value="">Please select</option></select></div><div class="col-md-1"><select class="order_item form-control select2" name="item_name[]" required><option value="">Please select</option></select></div><div class="col-md-1"><input class="form-control in_stock" type="number" name="item_stock[]" disabled /></div><div class="col-md-1"><input class="form-control min" type="text" name="item_price[]" disabled /></div><div class="col-md-1"><input class="form-control max" type="text" name="item_max_price[]" disabled /></div><div class="col-md-1"><input class="form-check-input cb ml-0" type="checkbox" name="is_box[]"/><label class="form-check-label ml-3">Is Box</label><input type="hidden" id="package_val" value="" name="package_val" /></div><div class="col-md-1"><input class="form-control quantity" type="number" name="item_quantity[]" min="1" required /><span class="text-danger qty_err"></span></div><div class="col-md-1"><input class="form-control sale_price" type="text" name="item_sale_priec[]"  required /><span class="text-danger sale_price_err"></span></div><div class="col-md-1"><?php echo $tax_ddl_html;?><input type="hidden" class="tax_val" value="" /></div><div class="col-md-1"><input class="form-control amount" type="text" name="item_amount[]" disabled /></div><div class="col-md-1"><span class="remove_row" id="remove_row">-</span></div></div>';
		}
		
		$(document).on("change", ".order_item", function () {
			if($(this).val() != ""){
				var stock = $(this).parent().next().find('input');				
				var min_selling_price = stock.parent().next().find('input');				
				var max_selling_price = min_selling_price.parent().next().find('input');
				var package_val = max_selling_price.parent().next().find('input[type=hidden]');
				var taxt_ddl = package_val.parent().next().next().next().find('select');
				var tax_field = package_val.parent().next().next().next().find('input[type=hidden]');

				$.ajax({
						url: 'get_product_detail/'+$(this).val(),
						type: 'GET',
						success: function(data) {
							if (data.success) {
								stock.val(data.product.stock);
								min_selling_price.val(data.product.selling_price);
								max_selling_price.val(data.product.maximum_selling_price);
								package_val.val(data.product.box_size);
								taxt_ddl.val(data.product.tax_id).change();
							}
						}
					 });
			}
					
		});
		
		$(document).on("keyup", ".quantity, .sale_price", function () {
			var qty = $(this).parent().parent().find(".quantity").val();
			var sale_price = $(this).parent().parent().find(".sale_price").val();
			var min_sale_price = $(this).parent().parent().find(".min").val();
			var in_stock = $(this).parent().parent().find(".in_stock").val();
			var quantity = 0;

			if((parseFloat(sale_price) < parseFloat(min_sale_price))){
				$(this).parent().parent().find(".sale_price_err").html("Sales Price can't be less than Min Selling Price");
			}else{				
				$(this).parent().parent().find(".sale_price_err").html("");
			}
			
			
			if($(this).parent().parent().find(".cb").is(':checked')){
				quantity = qty * $(this).parent().parent().find("#package_val").val();
			}else{
				quantity = qty;
			}
			
			if((parseFloat(quantity) > parseFloat(in_stock))){
				$(this).parent().parent().find(".qty_err").html("Quantity can't be greater than In Stock");
			}else{				
				$(this).parent().parent().find(".qty_err").html("");
			}
			
			if($(this).parent().parent().find(".cb").is(':checked')){
				qty = qty * $(this).parent().parent().find("#package_val").val();
			}		
			var tax = $(this).parent().parent().find(".tax_val").val();
			
			if(qty !="" && sale_price !=""){
				$(this).parent().parent().find(".amount").val(qty * sale_price);
			}

			if(tax !=""){
				var amount = qty * sale_price;
				
				amount = amount + ((amount * tax) / 100);
				
				$(this).parent().parent().find(".amount").val(amount);
			}
			calculate_total();
			
		});
		$(document).on("keyup", "#extra_discount", function () {
			calculate_total();
		});
		
		$(document).on("change", ".cb", function () {
			
			var qty = $(this).parent().parent().find(".quantity").val();
			var sale_price = $(this).parent().parent().find(".sale_price").val();
			var min_sale_price = $(this).parent().parent().find(".min").val();
			
			var in_stock = $(this).parent().parent().find(".in_stock").val();
			var quantity = 0;

			if((parseFloat(sale_price) < parseFloat(min_sale_price))){
				$(this).parent().parent().find(".sale_price_err").html("Sales Price can't be less than Min Selling Price");
			}else{				
				$(this).parent().parent().find(".sale_price_err").html("");
			}
			
			
			if($(this).parent().parent().find(".cb").is(':checked')){
				quantity = qty * $(this).parent().parent().find("#package_val").val();
			}else{
				quantity = qty;
			}
			
			if((parseFloat(quantity) > parseFloat(in_stock))){
				$(this).parent().parent().find(".qty_err").html("Quantity can't be greater than In Stock");
			}else{				
				$(this).parent().parent().find(".qty_err").html("");
			}
			
			if($(this).parent().parent().find(".cb").is(':checked')){
				qty = qty * $(this).parent().parent().find("#package_val").val();
			}			
			
			var tax = $(this).parent().parent().find(".tax_val").val();			
						
			if(qty !="" && sale_price !=""){
				$(this).parent().parent().find(".amount").val(qty * sale_price);
			}
			
			if(tax !=""){
				var amount = qty * sale_price;
				
				amount = amount + ((amount * tax) / 100);
				
				$(this).parent().parent().find(".amount").val(amount);
			}
			calculate_total();
		});		
		
		function calculate_total(){
			var order_total = 0;
			
			$(".amount").each(function() {
			
				order_total += parseFloat($(this).val());
			});
			if($("#extra_discount").val() > 0){
				order_total = order_total - $("#extra_discount").val();
			}
			$("#order_total").val(order_total);
		}
		
		$(document).on("change", ".subcat", function () {
			var cat_id = $(this).parent().prev('div').find('.category').val();
			var pdod_ddl = $(this).parent().next('div').find('.order_item');
			if(cat_id != "" && $(this).val() != ""){
				populate_products(cat_id, $(this).val(), pdod_ddl);
			}
		});

		$(document).on("change", ".category", function () {
			
			if($(this).val() !=""){
				
				var cat_id = $(this).val();
				var subcat_ddl =$(this).closest('.cat_container').next('div').find(".subcat");
				var product_ddl =$(this).closest('.cat_container').next('div').next('div').find(".order_item");
				
				$.ajax({
					url: '/admin/categories/get_sub_category/'+cat_id,
					type: 'GET',
					success: function(data) {
						if (data.success) {
							var html = '<option value="">Please select</option>';
							if(data.subcategories.length > 0){
								$.each(data.subcategories, function (key, val) {
									html += '<option value="'+val.id+'">'+val.name+'</option>';
								});
							}
							subcat_ddl.html(html);
							populate_products(cat_id, 0, product_ddl);
						}
					}
				 });
			}			
		
		});
	
		function populate_products(cat_id, sub_cat_id = 0, prod_ddl){	
	
			$.ajax({
				url: '/admin/inventories/get_products/'+cat_id+'/'+sub_cat_id,
				type: 'GET',
				success: function(data) {
					if (data.success) {
						var html = '<option value="">Please select</option>';
						if(data.products.length > 0){
							$.each(data.products, function (key, val) {
								html += '<option value="'+val.id+'">'+val.name+'</option>';
							});
						}
						prod_ddl.html(html);
					}
				}
			 });
		}
	
	$(document).on("change", ".tax_id", function () {

		var tax_id;
		tax_id = $(this).val();
		var qty = $(this).parent().parent().find(".quantity").val();
		var sale_price = $(this).parent().parent().find(".sale_price").val();
		
		var min_sale_price = $(this).parent().parent().find(".min").val();
		var in_stock = $(this).parent().parent().find(".in_stock").val();
		var quantity = 0;

		if((parseFloat(sale_price) < parseFloat(min_sale_price))){
			$(this).parent().parent().find(".sale_price_err").html("Sales Price can't be less than Min Selling Price");
		}else{				
			$(this).parent().parent().find(".sale_price_err").html("");
		}
		
		
		if($(this).parent().parent().find(".cb").is(':checked')){
			quantity = qty * $(this).parent().parent().find("#package_val").val();
		}else{
			quantity = qty;
		}
		
		if((parseFloat(quantity) > parseFloat(in_stock))){
			$(this).parent().parent().find(".qty_err").html("Quantity can't be greater than In Stock");
		}else{				
			$(this).parent().parent().find(".qty_err").html("");
		}
		
		//if(tax_id != "" && qty != "" && sale_price != ""){
		if(tax_id != ""){

			if($(this).parent().parent().find(".cb").is(':checked')){
				qty = qty * $(this).parent().parent().find("#package_val").val();
			}
			
			var tax = $(this).parent().find(".tax_val");
			var amount = $(this).parent().parent().find(".amount");

			$.ajax({
					url: '/admin/taxes/get_tax/'+tax_id,
					type: 'GET',
					success: function(data) {
						if (data.success) {					
							tax.val(data.tax.tax);
							var item_total = qty * sale_price;
							amount.val(item_total + ((item_total * data.tax.tax) / 100))
							calculate_total();
						}
					}
				 });
		}
	});

	$( "#orderfrm" ).on( "submit", function( event ) {
	 
	  var is_arror = false;
	  $('span.text-danger').each(function() {
		  if(!($(this).is(':empty'))){
			 is_arror = true;
			  return false;
		  }
		});
	  if(is_arror){		  
		   event.preventDefault();  
	  }
	});
		
	</script>
@endsection