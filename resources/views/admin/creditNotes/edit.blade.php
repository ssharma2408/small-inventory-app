@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.creditNote.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.credit-notes.update", [$creditNote->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="order_id">{{ trans('cruds.creditNote.fields.order') }}</label>
                  
                    <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="order_id" id="order_id" required>
                       
                   
                    @foreach($orders as $id => $entry)
                  
                            <option value="{{ $id }}" {{ (old('order_id') ? old('order_id') : $creditNote->order->id ?? '') == $id ? 'selected' : '' }}>{{$entry->id}} -> {{ $entry->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('order'))
                        <span class="text-danger">{{ $errors->first('order') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.creditNote.fields.order_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="amount">{{ trans('cruds.creditNote.fields.amount') }}</label>
                    <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', $creditNote->amount) }}" step="0.01" required>
                    @if($errors->has('amount'))
                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.creditNote.fields.amount_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="date">{{ trans('cruds.creditNote.fields.date') }}</label>
                    <input class="form-control datetime {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $creditNote->date) }}" required>
                    @if($errors->has('date'))
                        <span class="text-danger">{{ $errors->first('date') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.creditNote.fields.date_helper') }}</span>
                </div>
                <div class="form-group col-lg-12">
                    <label for="description">{{ trans('cruds.creditNote.fields.description') }}</label>
                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $creditNote->description) }}</textarea>
                    @if($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.creditNote.fields.description_helper') }}</span>
                </div>
             
                <div class="form-group  col-lg-12">
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