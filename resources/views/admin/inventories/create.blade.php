@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.inventory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.inventories.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="supplier_id">{{ trans('cruds.inventory.fields.supplier') }}</label>
                <select class="form-control select2 {{ $errors->has('supplier') ? 'is-invalid' : '' }}" name="supplier_id" id="supplier_id">
                    @foreach($suppliers as $id => $entry)
                        <option value="{{ $id }}" {{ old('supplier_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('supplier'))
                    <span class="text-danger">{{ $errors->first('supplier') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.supplier_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="product_id">{{ trans('cruds.inventory.fields.product') }}</label>
                <select class="form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}" name="product_id" id="product_id" required>
                    @foreach($products as $id => $entry)
                        <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product'))
                    <span class="text-danger">{{ $errors->first('product') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.product_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="stock">{{ trans('cruds.inventory.fields.stock') }}</label>
                <input class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}" type="number" name="stock" id="stock" value="{{ old('stock', '') }}" step="1" required>
                @if($errors->has('stock'))
                    <span class="text-danger">{{ $errors->first('stock') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.stock_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="purchase_price">{{ trans('cruds.inventory.fields.purchase_price') }}</label>
                <input class="form-control {{ $errors->has('purchase_price') ? 'is-invalid' : '' }}" type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', '') }}" step="0.01" required>
                @if($errors->has('purchase_price'))
                    <span class="text-danger">{{ $errors->first('purchase_price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.purchase_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.inventory.fields.discount_type') }}</label>
                @foreach(App\Models\Inventory::DISCOUNT_TYPE_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('discount_type') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="discount_type_{{ $key }}" name="discount_type" value="{{ $key }}" {{ old('discount_type', '0') === (string) $key ? 'checked' : '' }} required>
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
                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount', '0') }}" step="0.01">
                @if($errors->has('discount'))
                    <span class="text-danger">{{ $errors->first('discount') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.discount_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="tax">{{ trans('cruds.inventory.fields.tax') }}</label>
                <input class="form-control {{ $errors->has('tax') ? 'is-invalid' : '' }}" type="number" name="tax" id="tax" value="{{ old('tax', '') }}" step="0.01" required>
                @if($errors->has('tax'))
                    <span class="text-danger">{{ $errors->first('tax') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.tax_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="final_price">{{ trans('cruds.inventory.fields.final_price') }}</label>
                <input class="form-control {{ $errors->has('final_price') ? 'is-invalid' : '' }}" type="number" name="final_price" id="final_price" value="{{ old('final_price', '') }}" step="0.01" required>
                @if($errors->has('final_price'))
                    <span class="text-danger">{{ $errors->first('final_price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.final_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="po_file">{{ trans('cruds.inventory.fields.po_file') }}</label>
                <div class="needsclick dropzone {{ $errors->has('po_file') ? 'is-invalid' : '' }}" id="po_file-dropzone">
                </div>
                @if($errors->has('po_file'))
                    <span class="text-danger">{{ $errors->first('po_file') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.po_file_helper') }}</span>
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
	tax = $("#tax").val();
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
			order_total = order_total + parseFloat(tax);
		}
	}
	$("#final_price").val(order_total);
}
</script>
@endsection