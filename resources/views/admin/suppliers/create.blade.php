@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.supplier.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.suppliers.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                    <label class="required" for="supplier_name">{{ trans('cruds.supplier.fields.supplier_name') }}</label>
                    <input class="form-control {{ $errors->has('supplier_name') ? 'is-invalid' : '' }}" type="text" name="supplier_name" id="supplier_name" value="{{ old('supplier_name', '') }}" required>
                    @if($errors->has('supplier_name'))
                        <span class="text-danger">{{ $errors->first('supplier_name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.supplier.fields.supplier_name_helper') }}</span>
                </div>
                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                    <label class="required" for="supplier_number">{{ trans('cruds.supplier.fields.supplier_number') }}</label>
                    <input class="form-control {{ $errors->has('supplier_number') ? 'is-invalid' : '' }}" type="text" name="supplier_number" id="supplier_number" value="{{ old('supplier_number', '') }}" required>
                    @if($errors->has('supplier_number'))
                        <span class="text-danger">{{ $errors->first('supplier_number') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.supplier.fields.supplier_number_helper') }}</span>
                </div>
                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                    <label for="supplier_email">{{ trans('cruds.supplier.fields.supplier_email') }}</label>
                    <input class="form-control {{ $errors->has('supplier_email') ? 'is-invalid' : '' }}" type="email" name="supplier_email" id="supplier_email" value="{{ old('supplier_email') }}">
                    @if($errors->has('supplier_email'))
                        <span class="text-danger">{{ $errors->first('supplier_email') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.supplier.fields.supplier_email_helper') }}</span>
                </div>
                <div class="form-group col-lg-12">
                    <button class="btn btn-danger mr-2" type="submit">
                        {{ trans('global.save') }}
                    </button>
                    <a href="{{url()->previous()}}" class="btn btn-default ">{{ trans('global.cancel') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>



@endsection