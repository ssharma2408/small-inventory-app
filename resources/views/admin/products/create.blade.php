@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.product.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.products.store") }}" enctype="multipart/form-data">
            @csrf
			<div class="form-group">
                <label class="required" for="category_id">{{ trans('cruds.category.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                    <option value="" >Select Option</option> 
                    @foreach($categories as $id => $entry)
                     @php $level=1; @endphp
                       <option value="{{ $entry->id }}" {{ old('category_id') == $entry->id ? 'selected' : '' }}>{{ $entry->name }}</option> 
                       @if(count($entry->childCategories) > 0)
                         @include('admin.categories.subcategories', ['category' => $entry,'selected'=>'0']);
                        @endif
                        
                    @endforeach
                </select>
				<div><a href="{{ route("admin.categories.create") }}/?redirect=add-product">Add Category</a></div>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.category_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="maximum_selling_price">{{ trans('cruds.product.fields.maximum_selling_price') }}</label>
                <input class="form-control {{ $errors->has('maximum_selling_price') ? 'is-invalid' : '' }}" type="number" name="maximum_selling_price" id="maximum_selling_price" value="{{ old('maximum_selling_price', '') }}" step="0.01" required>
                @if($errors->has('maximum_selling_price'))
                    <span class="text-danger">{{ $errors->first('maximum_selling_price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.maximum_selling_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="selling_price">{{ trans('cruds.product.fields.selling_price') }}</label>
                <input class="form-control {{ $errors->has('selling_price') ? 'is-invalid' : '' }}" type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', '') }}" step="0.01" required>
                @if($errors->has('selling_price'))
                    <span class="text-danger">{{ $errors->first('selling_price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.selling_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="stock">{{ trans('cruds.product.fields.stock') }}</label>
                <input class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}" type="number" name="stock" id="stock" value="0" disabled>
                @if($errors->has('stock'))
                    <span class="text-danger">{{ $errors->first('stock') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.stock_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="product_image">{{ trans('cruds.product.fields.product_image') }}</label>                
				<input class="form-control" type="file" name="product_image" id="product_image" required />
                @if($errors->has('product_image'))
                    <span class="text-danger">{{ $errors->first('product_image') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.product_image_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="box_size">{{ trans('cruds.product.fields.box_size') }}</label>
                <input class="form-control {{ $errors->has('box_size') ? 'is-invalid' : '' }}" type="number" name="box_size" id="box_size" value="{{ old('box_size', '') }}" step="1" required>
                @if($errors->has('box_size'))
                    <span class="text-danger">{{ $errors->first('box_size') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.box_size_helper') }}</span>
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
@if(isset($product) && $product->product_image)
      var file = {!! json_encode($product->product_image) !!}
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

</script>
@endsection