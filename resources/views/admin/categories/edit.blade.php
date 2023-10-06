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
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.category.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="category_order">{{ trans('cruds.category.fields.category_order') }}</label>
                <input class="form-control {{ $errors->has('category_order') ? 'is-invalid' : '' }}" type="number" name="category_order" id="category_order" value="{{ old('category_order', $category->category_order) }}" step="1" required>
                @if($errors->has('category_order'))
                    <span class="text-danger">{{ $errors->first('category_order') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.category_order_helper') }}</span>
            </div>
			<div class="form-group">
                <label for="category_id">{{ trans('cruds.category.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id">

                    <option value="">Select Option</option>
                    @foreach($categories as $id => $entry)
                    @php $level=1; @endphp
                    <option value="{{ $entry->id }}" {{ (old('category_id') ? old('category_id') : $category->category->id ?? '') == $entry->id ? 'selected' : '' }}>{{ $entry->name }}</option>

                    @if(count($entry->childCategories) > 0)
                    @include('admin.categories.subcategories', ['category' => $entry,'selected'=>isset($category->category->id)?$category->category->id:"" ]);
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
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection