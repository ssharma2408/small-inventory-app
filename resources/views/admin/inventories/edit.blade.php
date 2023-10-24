@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.inventory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.inventories.update", [$inventory->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="supplier_id">{{ trans('cruds.inventory.fields.supplier') }}</label>
                <select class="form-control select2 {{ $errors->has('supplier') ? 'is-invalid' : '' }}" name="supplier_id" id="supplier_id" disabled>
                    @foreach($suppliers as $id => $entry)
                        <option value="{{ $id }}" {{ (old('supplier_id') ? old('supplier_id') : $inventory->supplier->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('supplier'))
                    <span class="text-danger">{{ $errors->first('supplier') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.supplier_helper') }}</span>
            </div>
			<div class="form-group">
                <label class="required" for="category_id">{{ trans('cruds.category.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>

                    <option value="">Select Option</option>
                    @foreach($categories as $id => $entry)                    
                    <option value="{{ $id }}" {{ (old('category_id') ? old('category_id') : $inventory->category_id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>

                    @endforeach
                </select>
                @if($errors->has('category'))
                <div class="invalid-feedback">
                    {{ $errors->first('category') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.category_helper') }}</span>
            </div>
			<div class="form-group">
                <label for="sub_category_id">{{ trans('cruds.category.fields.sub_category') }}</label>
                <select class="form-control select2 {{ $errors->has('subcategory') ? 'is-invalid' : '' }}" name="sub_category_id" id="sub_category_id">
                    <option value="" >Please select</option>                      
                </select>				
                @if($errors->has('subcategory'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subcategory') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.sub_category_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="product_id">{{ trans('cruds.inventory.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id" required>
                   <option value="">Please select</option>
                </select>
                @if($errors->has('product'))
                    <span class="text-danger">{{ $errors->first('product') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.product_helper') }}</span>
            </div>
			<div class="form-group">
                <label class="required" for="invoice_number">{{ trans('cruds.inventory.fields.invoice_number') }}</label>
                <input class="form-control {{ $errors->has('invoice_number') ? 'is-invalid' : '' }}" type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $inventory->invoice_number) }}" step="1" disabled>
                @if($errors->has('invoice_number'))
                    <span class="text-danger">{{ $errors->first('invoice_number') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.invoice_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.inventory.fields.box_or_unit') }}</label>
                @foreach(App\Models\Inventory::BOX_OR_UNIT_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('box_or_unit') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="box_or_unit_{{ $key }}" name="box_or_unit" value="{{ $key }}" {{ old('box_or_unit', $inventory->box_or_unit) === (string) $key ? 'checked' : '' }} required disabled>
                        <label class="form-check-label" for="box_or_unit_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('box_or_unit'))
                    <span class="text-danger">{{ $errors->first('box_or_unit') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.box_or_unit_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="stock">{{ trans('cruds.inventory.fields.stock') }}</label>
                <input class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}" type="number" name="stock" id="stock" value="{{ old('stock', $inventory->stock) }}" step="1" required>
                @if($errors->has('stock'))
                    <span class="text-danger">{{ $errors->first('stock') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.stock_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="purchase_price">{{ trans('cruds.inventory.fields.purchase_price') }}</label>
                <input class="form-control {{ $errors->has('purchase_price') ? 'is-invalid' : '' }}" type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $inventory->purchase_price) }}" step="0.01" required>
                @if($errors->has('purchase_price'))
                    <span class="text-danger">{{ $errors->first('purchase_price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.purchase_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.inventory.fields.discount_type') }}</label>
                @foreach(App\Models\Inventory::DISCOUNT_TYPE_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('discount_type') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="discount_type_{{ $key }}" name="discount_type" value="{{ $key }}" {{ old('discount_type', $inventory->discount_type) === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="discount_type_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('discount_type'))
                    <span class="text-danger">{{ $errors->first('discount_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.discount_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="discount">{{ trans('cruds.inventory.fields.discount') }}</label>
                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount', $inventory->discount) }}" step="0.01">
                @if($errors->has('discount'))
                    <span class="text-danger">{{ $errors->first('discount') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.discount_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="tax_id">{{ trans('cruds.inventory.fields.tax') }}</label>
                <select class="form-control select2 {{ $errors->has('tax') ? 'is-invalid' : '' }}" name="tax_id" id="tax_id" required>
                    @foreach($taxes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('tax_id') ? old('tax_id') : $inventory->tax->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('tax'))
                    <span class="text-danger">{{ $errors->first('tax') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.tax_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="final_price">{{ trans('cruds.inventory.fields.final_price') }}</label>
                <input class="form-control {{ $errors->has('final_price') ? 'is-invalid' : '' }}" type="number" name="final_price" id="final_price" value="{{ old('final_price', $inventory->final_price) }}" step="0.01" required>
                @if($errors->has('final_price'))
                    <span class="text-danger">{{ $errors->first('final_price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.final_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.inventory.fields.days_payable_outstanding') }}</label>
                <select class="form-control {{ $errors->has('days_payable_outstanding') ? 'is-invalid' : '' }}" name="days_payable_outstanding" id="days_payable_outstanding" required>
                    <option value disabled {{ old('days_payable_outstanding', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Inventory::DAYS_PAYABLE_OUTSTANDING_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('days_payable_outstanding', $inventory->days_payable_outstanding) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('days_payable_outstanding'))
                    <span class="text-danger">{{ $errors->first('days_payable_outstanding') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.days_payable_outstanding_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="po_file">{{ trans('cruds.inventory.fields.po_file') }}</label>
                <input class="form-control {{ $errors->has('po_file') ? 'is-invalid' : '' }}" type="file" name="po_file" id="po_file" value="{{ old('po_file', $inventory->po_file) }}" />
                @if($errors->has('po_file'))
                    <span class="text-danger">{{ $errors->first('po_file') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.po_file_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
				<input type="hidden" name="image_url" value="{{$inventory->image_url}}">
				<input type="hidden" id="tax_val" value="" />
				<input type="hidden" id="package_val" value="" name="package_val" />
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    Dropzone.options.poFileDropzone = {
    url: '{{ route('admin.inventories.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="po_file"]').remove()
      $('form').append('<input type="hidden" name="po_file" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="po_file"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($inventory) && $inventory->po_file)
      var file = {!! json_encode($inventory->po_file) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="po_file" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}

$(function() {
    populate_products($("#category_id").val());
	load_package_val(<?php echo $inventory->product_id; ?>);
	load_tax_val(<?php echo $inventory->tax->id; ?>);	 
});

$(document).on("change", "#discount_type_0, #discount_type_1", function () {
	calculate_total();
});

$(document).on("keyup", "#stock, #purchase_price, #tax, #discount", function () {			
	calculate_total();
});

function calculate_total(){
	var order_total = 0, stock, price, tax, discount;		
	stock = $("#stock").val();
	price = $("#purchase_price").val();
	tax = $("#tax_val").val();
	discount = $("#discount").val();
	
	if(stock > 0 && price > 0){
		order_total = stock * price;				
		
		if(discount > 0){
			if($("#discount_type_0").is(":checked")){
				if(discount < order_total){
					order_total = order_total - discount;
				}
			}else{
				if(((order_total * discount) / 100) < order_total){
					order_total = order_total - (order_total * discount) / 100;
				}
			}
		}
		
		if(tax > 0){
			order_total = order_total + (order_total * parseFloat(tax)) / 100;
		}
	}
	$("#final_price").val(order_total);
}

function populate_products(cat_id){
	$.ajax({
			url: '/admin/inventories/get_products/'+cat_id,
			type: 'GET',
			success: function(data) {
				if (data.success) {
					if(data.products.length > 0){
						var html = '<option value="">Please select</option>';
						$.each(data.products, function (key, val) {
							var selected = "";
							if(val.id === <?php echo $inventory->product_id; ?>){
								selected = "selected";
							}
							
							
							html += '<option value="'+val.id+'" '+selected+'>'+val.name+'</option>';
						});
						$("#product_id").html(html);
					}else{
						//
					}
				}
			}
		 });
}

$("#category_id").change(function (){
	populate_products($(this).val());
});


function load_package_val(prod_id){
	if(prod_id != ""){
	$.ajax({
			url: '/admin/products/get_drod_detail/'+prod_id,
			type: 'GET',
			success: function(data) {
				if (data.success) {
					$("#package_val").val(data.product.box_size);
				}
			}
		 });
	}
}

function load_tax_val(tax_id){
	if(tax_id != ""){
		$.ajax({
				url: '/admin/taxes/get_tax/'+tax_id,
				type: 'GET',
				success: function(data) {
					if (data.success) {
						$("#tax_val").val(data.tax.tax);
						calculate_total();
					}
				}
			 });
	}
}

$(document).on("change", "#product_id", function () {
	load_package_val($(this).val());
});
$(document).on("change", "#tax_id", function () {
	load_tax_val($(this).val());
});
</script>
@endsection