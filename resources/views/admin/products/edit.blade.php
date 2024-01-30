@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.product.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.products.update", [$product->id]) }}"
            enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="category_id">{{ trans('cruds.category.fields.category') }}</label>
                    <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}"
                        name="category_id" id="category_id" required>

                        <option value="">Select Option</option>
                        @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('category_id') ? old('category_id') : $product->category_id ??
                            '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    <div><a href="{{ route("admin.categories.create")
                            }}/?redirect=edit-product&id={{$product->id}}">Add Category</a></div>
                    @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.category.fields.category_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label for="sub_category_id">{{ trans('cruds.category.fields.sub_category') }}</label>
                    <select class="form-control select2 {{ $errors->has('subcategory') ? 'is-invalid' : '' }}"
                        name="sub_category_id" id="sub_category_id">
                        <option value="">Please select</option>
                    </select>
                    @if($errors->has('subcategory'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subcategory') }}
                    </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.category.fields.sub_category_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', $product->name) }}" required>
                    @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="maximum_selling_price">{{
                        trans('cruds.product.fields.maximum_selling_price') }}</label>
                    <input class="form-control {{ $errors->has('maximum_selling_price') ? 'is-invalid' : '' }}"
                        type="number" name="maximum_selling_price" id="maximum_selling_price"
                        value="{{ old('maximum_selling_price', $product->maximum_selling_price) }}" step="0.01"
                        required>
                    @if($errors->has('maximum_selling_price'))
                    <span class="text-danger">{{ $errors->first('maximum_selling_price') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.maximum_selling_price_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="selling_price">{{ trans('cruds.product.fields.selling_price')
                        }}</label>
                    <input class="form-control {{ $errors->has('selling_price') ? 'is-invalid' : '' }}" type="number"
                        name="selling_price" id="selling_price"
                        value="{{ old('selling_price', $product->selling_price) }}" step="0.01" required>
                    @if($errors->has('selling_price'))
                    <span class="text-danger">{{ $errors->first('selling_price') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.selling_price_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label for="stock_val">{{ trans('cruds.product.fields.stock') }}</label>
                    <input class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}" type="text"
                        name="stock_val" id="stock_val" value="{{ old('stock_val', $product->stock) }}" disabled>
                    @if($errors->has('stock'))
                    <span class="text-danger">{{ $errors->first('stock') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.stock_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="tax_id">{{ trans('cruds.product.fields.tax') }}</label>
                    <select class="form-control select2 {{ $errors->has('tax') ? 'is-invalid' : '' }}" name="tax_id"
                        id="tax_id" required>
                        @foreach($taxes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('tax_id') ? old('tax_id') : $product->tax->id ?? '') == $id ?
                            'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('tax'))
                    <span class="text-danger">{{ $errors->first('tax') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.tax_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="" for="product_image">{{ trans('cruds.product.fields.product_image') }}</label>
                    <input class="form-control {{ $errors->has('product_image') ? 'is-invalid' : '' }}" type="file"
                        name="product_image" id="product_image"
                        value="{{ old('product_image', $product->product_image) }}" />
                    @if($errors->has('product_image'))
                    <span class="text-danger">{{ $errors->first('product_image') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.product_image_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="box_size">{{ trans('cruds.product.fields.box_size') }}</label>
                    <input class="form-control {{ $errors->has('box_size') ? 'is-invalid' : '' }}" type="number"
                        name="box_size" id="box_size" value="{{ old('box_size', $product->box_size) }}" step="1"
                        required>
                    @if($errors->has('box_size'))
                    <span class="text-danger">{{ $errors->first('box_size') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.box_size_helper') }}</span>
                </div>
                <div class="form-group col-lg-12">
                    <button class="btn btn-danger mr-2" type="submit">
                        {{ trans('global.save') }}
                    </button>
                    <a href="{{url()->previous()}}" class="btn btn-default ">{{ trans('global.cancel') }}</a>
                    <input type="hidden" name="image_url" value="{{$product->image_url}}">
                    <input type="hidden" name="stock" value="{{$product->stock}}">
                </div>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    Dropzone.options.productImageDropzone = {
        url: '{{ route('admin.products.storeMedia') }}',
        maxFilesize: 2, // MB
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        maxFiles: 1,
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {
            size: 2,
            width: 4096,
            height: 4096
        },
        success: function (file, response) {
            $('form').find('input[name="product_image"]').remove()
            $('form').append('<input type="hidden" name="product_image" value="' + response.name + '">')
        },
        removedfile: function (file) {
            file.previewElement.remove()
            if (file.status !== 'error') {
                $('form').find('input[name="product_image"]').remove()
                this.options.maxFiles = this.options.maxFiles + 1
            }
        },
        init: function () {
            @if (isset($product) && $product -> product_image)
                var file = {!! json_encode($product -> product_image)!!
        }
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="product_image" value="' + file.file_name + '">')
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
    $(function () {
        $.ajax({
            url: '/admin/categories/get_sub_category/' +<?php echo $product-> category_id; ?>,
                type: 'GET',
                    success: function(data) {
                        if (data.success) {
                            var html = '<option value="">Please select</option>';
                            if (data.subcategories.length > 0) {
                                $.each(data.subcategories, function (key, val) {
                                    var selected = "";

                                    if (val.id == '<?php echo $product->sub_category_id; ?>') {
                                        selected = "selected";
                                    }
                                    html += '<option value="' + val.id + '" ' + selected + '>' + val.name + '</option>';
                                });
                            }
                            $("#sub_category_id").html(html);
                        }
                    }
    });	 
});

</script>
@endsection