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
                <label class="required" for="selling_price">{{ trans('cruds.product.fields.selling_price') }}</label>
                <input class="form-control {{ $errors->has('selling_price') ? 'is-invalid' : '' }}" type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', '') }}" step="0.01" required>
                @if($errors->has('selling_price'))
                    <span class="text-danger">{{ $errors->first('selling_price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.selling_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="stock">{{ trans('cruds.product.fields.stock') }}</label>
                <input class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}" type="number" name="stock" id="stock" value="{{ old('stock', '') }}" step="1" required>
                @if($errors->has('stock'))
                    <span class="text-danger">{{ $errors->first('stock') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.stock_helper') }}</span>
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