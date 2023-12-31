@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.tax.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.taxes.update", [$tax->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
            <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                <label class="required" for="title">{{ trans('cruds.tax.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $tax->title) }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tax.fields.title_helper') }}</span>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                <label class="required" for="tax">{{ trans('cruds.tax.fields.tax') }}</label>
                <input class="form-control {{ $errors->has('tax') ? 'is-invalid' : '' }}" type="number" name="tax" id="tax" value="{{ old('tax', $tax->tax) }}" step="0.01" required>
                @if($errors->has('tax'))
                    <span class="text-danger">{{ $errors->first('tax') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tax.fields.tax_helper') }}</span>
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