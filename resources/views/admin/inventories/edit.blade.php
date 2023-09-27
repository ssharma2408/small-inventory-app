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
                <select class="form-control select2 {{ $errors->has('supplier') ? 'is-invalid' : '' }}" name="supplier_id" id="supplier_id">
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
                <label class="required" for="product_name">{{ trans('cruds.inventory.fields.product_name') }}</label>
                <input class="form-control {{ $errors->has('product_name') ? 'is-invalid' : '' }}" type="text" name="product_name" id="product_name" value="{{ old('product_name', $inventory->product_name) }}" required>
                @if($errors->has('product_name'))
                    <span class="text-danger">{{ $errors->first('product_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.product_name_helper') }}</span>
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
                <label class="required" for="price">{{ trans('cruds.inventory.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', $inventory->price) }}" step="0.01" required>
                @if($errors->has('price'))
                    <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.inventory.fields.price_helper') }}</span>
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
                <label class="required" for="tax">{{ trans('cruds.inventory.fields.tax') }}</label>
                <input class="form-control {{ $errors->has('tax') ? 'is-invalid' : '' }}" type="number" name="tax" id="tax" value="{{ old('tax', $inventory->tax) }}" step="0.01" required>
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
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection