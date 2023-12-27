@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.category.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.categories.store") }}" enctype="multipart/form-data" width="100%">
            @csrf
            <div class="row">
            <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                <label for="category_id">{{ trans('cruds.category.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id">
                    <option value="" >Select Option</option> 
                    @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>                      
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.category_helper') }}</span>
            </div>
			<div class="form-group col-lg-6  col-md-6 col-sm-12">
                <label class="required" for="name">{{ trans('cruds.category.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.name_helper') }}</span>
            </div>	
           	
            <div class="form-group col-12">
             
                <button class="btn btn-danger mr-2" type="submit">
                    {{ trans('global.save') }}
                </button>
              
                <a href="{{url()->previous()}}" class="btn btn-default ">{{ trans('global.cancel') }}</a>
				<input type="hidden" name="redirect" value="{{ app('request')->input('redirect') }}">				
				<input type="hidden" name="redirect_id" value="{{ app('request')->input('id') }}" />
            </div>
            </div>	
        </form>
    </div>
</div>



@endsection