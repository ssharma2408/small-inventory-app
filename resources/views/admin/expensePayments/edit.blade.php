@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.expensePayment.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.expense-payments.update", [$expensePayment->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="invoice_id">{{ trans('cruds.expensePayment.fields.invoice') }}</label>
                <select class="form-control select2 {{ $errors->has('invoice') ? 'is-invalid' : '' }}" name="invoice_id" id="invoice_id" required disabled>
                    <option>Please Select</option>
					@foreach($invoices as $entry)
                        <option value="{{ $entry->expense_id }}" {{ (old('invoice_id') ? old('invoice_id') : $expensePayment->expense_id ?? '') == $entry->expense_id ? 'selected' : '' }}>{{$entry->invoice_number}} -> {{ $entry->supplier_name }}</option>
                    @endforeach
                </select>
                @if($errors->has('invoice'))
                    <span class="text-danger">{{ $errors->first('invoice') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.expensePayment.fields.invoice_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="payment_id">{{ trans('cruds.expensePayment.fields.payment') }}</label>
                <select class="form-control select2 {{ $errors->has('payment') ? 'is-invalid' : '' }}" name="payment_id" id="payment_id" required>
                    @foreach($payments as $id => $entry)
                        <option value="{{ $id }}" {{ (old('payment_id') ? old('payment_id') : $expensePayment->payment->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('payment'))
                    <span class="text-danger">{{ $errors->first('payment') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.expensePayment.fields.payment_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="amount">{{ trans('cruds.expensePayment.fields.amount') }}</label>
                <div id="due_amount"></div>
				<input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', $expensePayment->amount) }}" step="0.01" required disabled>
                @if($errors->has('amount'))
                    <span class="text-danger">{{ $errors->first('amount') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.expensePayment.fields.amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.expensePayment.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $expensePayment->description) }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.expensePayment.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="date">{{ trans('cruds.expensePayment.fields.date') }}</label>
                <input class="form-control datetime {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $expensePayment->date) }}" required>
                @if($errors->has('date'))
                    <span class="text-danger">{{ $errors->first('date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.expensePayment.fields.date_helper') }}</span>
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
$(function() {
	$.ajax({
		url: '/admin/expense-payments/get_due_payment/'+<?php echo $expensePayment->expense_id; ?>,
		type: 'GET',
		success: function(data) {
			if (data.success) {
				$("#due_amount").html('Pending Amount: <b>'+data.due_amount.expense_pending+'</b>');
			}
		}
	 });
});
</script>
@endsection