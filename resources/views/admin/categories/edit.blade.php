@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.category.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.categories.update", [$category->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
            <div class="form-group  col-lg-4 col-md-4 col-sm-12 ">
                <label for="category_id">{{ trans('cruds.category.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id">

                    <option value="">Select Option</option>
                    @foreach($categories as $id => $entry)                    
                    <option value="{{ $id }}" {{ (old('category_id') ? old('category_id') : $category->category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                <div class="invalid-feedback">
                    {{ $errors->first('category') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.category_helper') }}</span>
            </div>
			<div class="form-group  col-lg-4 col-md-4 col-sm-12 ">
                <label class="required" for="name">{{ trans('cruds.category.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.name_helper') }}</span>
            </div>
			
			<div class="form-group col-lg-4 col-md-4 col-sm-12">
				<label class="" for="category_image">{{ trans('cruds.category.fields.category_image') }}</label>
				<input class="form-control {{ $errors->has('category_image') ? 'is-invalid' : '' }}" type="file"
					name="category_image" id="category_image"
					value="{{ old('category_image', $category->category_image) }}" />
				@if($errors->has('category_image'))
				<span class="text-danger">{{ $errors->first('category_image') }}</span>
				@endif
				<span class="help-block">{{ trans('cruds.category.fields.category_image_helper') }}</span>
			</div>
			
			<div class="form-group col-lg-4 col-md-4 col-sm-12 ">
                <label for="show_fe">{{ trans('cruds.category.fields.show_fe') }}</label>
				<input class="form-check-input ml-2" type="checkbox" name="show_fe" id="show_fe" {{ $category->show_fe == 1 ? 'checked' : '' }} />
                @if($errors->has('show_fe'))
                    <span class="text-danger">{{ $errors->first('show_fe') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.show_fe_helper') }}</span>
            </div>
			
            <div class="form-group  col-lg-12 ">
                <button class="btn btn-danger mr-2" type="submit">
                    {{ trans('global.save') }}
                </button>
				<input type="hidden" name="image_url" value="{{$category->image_url}}">
                
                <a href="{{url()->previous()}}" class="btn btn-default">{{ trans('global.cancel') }}</a>
            </div>
            </div>
        </form>
    </div>
</div>



@endsection