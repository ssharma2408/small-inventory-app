@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.orderPayment.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" id="ordPay" action="{{ route("admin.order-payments.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="order_id">{{ trans('cruds.orderPayment.fields.order') }}</label>
                    <select class="form-control select2 {{ $errors->has('order') ? 'is-invalid' : '' }}" name="order_id" id="order_id" required>
                        <option>Please Select</option>
                        @foreach($orders as $entry)
                            <option value="{{ $entry->order_number }}" {{ old('order_id') == $entry->order_number ? 'selected' : '' }}>{{$entry->order_number}} -> {{ $entry->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('order'))
                        <span class="text-danger">{{ $errors->first('order') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.expensePayment.fields.invoice_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="payment_id">{{ trans('cruds.orderPayment.fields.payment') }}</label>
                    <select class="form-control select2 {{ $errors->has('payment') ? 'is-invalid' : '' }}" name="payment_id" id="payment_id" required>
                        @foreach($payments as $id => $entry)
                            <option value="{{ $id }}" {{ old('payment_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('payment'))
                        <span class="text-danger">{{ $errors->first('payment') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.orderPayment.fields.payment_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="amount">{{ trans('cruds.orderPayment.fields.amount') }}</label>
                    
                    <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01" required>
                    <div id="due_amount"></div>
                    <span class="text-danger amount_err"></span>
                    @if($errors->has('amount'))
                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.orderPayment.fields.amount_helper') }}</span>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label class="required" for="date">{{ trans('cruds.orderPayment.fields.date') }}</label>
                    <input class="form-control datetime {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date') }}" required>
                    @if($errors->has('date'))
                        <span class="text-danger">{{ $errors->first('date') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.orderPayment.fields.date_helper') }}</span>
                </div>
                <div class="form-group col-lg-12 ">
                    <label for="description">{{ trans('cruds.orderPayment.fields.description') }}</label>
                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
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

	$(document).on("keyup", "#amount", function () {
		var pending_amount = parseFloat($("#pending_amount").html());
		var entered_amount = parseFloat($(this).val());		
		if(pending_amount < entered_amount){
			$(".amount_err").html("Entered Amount can't be greater than Pending Amount");
		}else{				
			$(".amount_err").html("");
		}
	});

	$("#order_id").change(function (){
		
		if($(this).val() !=""){
			$.ajax({
				url: 'get_due_payment/'+$(this).val(),
				type: 'GET',
				success: function(data) {
					if (data.success) {
						$("#due_amount").html('Pending Amount: <b id="pending_amount">'+data.due_amount.order_pending+'</b>');
					}
				}
			 });
		}
	});

$( "#ordPay" ).on( "submit", function( event ) {
	 
	  var is_arror = false;
	  $('span.text-danger').each(function() {
		  if(!($(this).is(':empty'))){
			 is_arror = true;
			  return false;
		  }
		});
	  if(is_arror){		  
		   event.preventDefault();  
	  }
	});	
</script>
@endsection