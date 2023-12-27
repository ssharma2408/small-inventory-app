@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.orderPayment.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.order-payments.update", [$orderPayment->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf            
            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="order_id">{{ trans('cruds.orderPayment.fields.order') }}</label>
                    <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="order_id" id="order_id" required disabled >
                        <option>Please Select</option>
                        @foreach($orders as $entry)
                            <option value="{{ $entry->order_number }}" {{ (old('order_id') ? old('order_id') : $orderPayment->order_id ?? '') == $entry->order_number ? 'selected' : '' }}>{{$entry->order_number}} -> {{ $entry->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('order'))
                        <span class="text-danger">{{ $errors->first('order') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.orderPayment.fields.order_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="payment_id">{{ trans('cruds.orderPayment.fields.payment') }}</label>
                    <select class="form-control select2 {{ $errors->has('payment') ? 'is-invalid' : '' }}" name="payment_id" id="payment_id" required>
                        @foreach($payments as $id => $entry)
                            <option value="{{ $id }}" {{ (old('payment_id') ? old('payment_id') : $orderPayment->payment->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('payment'))
                        <span class="text-danger">{{ $errors->first('payment') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.orderPayment.fields.payment_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="amount">{{ trans('cruds.orderPayment.fields.amount') }}</label>
                    
                    <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', $orderPayment->amount) }}" step="0.01" required disabled>
                    <div id="due_amount"></div>
                    @if($errors->has('amount'))
                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.orderPayment.fields.amount_helper') }}</span>
                </div>
               
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="date">{{ trans('cruds.orderPayment.fields.date') }}</label>
                    <input class="form-control datetime {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $orderPayment->date) }}" required>
                    @if($errors->has('date'))
                        <span class="text-danger">{{ $errors->first('date') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.orderPayment.fields.date_helper') }}</span>
                </div>
                <div class="form-group col-lg-12">
                    <label for="description">{{ trans('cruds.orderPayment.fields.description') }}</label>
                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $orderPayment->description) }}</textarea>
                    @if($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.orderPayment.fields.description_helper') }}</span>
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

@section('scripts')
<script>		
$(function() {
	$.ajax({
		url: '/admin/order-payments/get_due_payment/'+<?php echo $orderPayment->order_id; ?>,
		type: 'GET',
		success: function(data) {
			if (data.success) {
				$("#due_amount").html('Pending Amount: <b>'+data.due_amount.order_pending+'</b>');
			}
		}
	 });
});
</script>
@endsection