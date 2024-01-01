<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditNoteRequest;
use App\Http\Requests\UpdateCreditNoteRequest;
use App\Http\Resources\Admin\CreditNoteResource;
use App\Models\CreditNote;
use App\Models\Order;
use App\Models\Customer;
use App\Models\CreditNoteLog;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreditNoteApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('credit_note_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CreditNoteResource(CreditNote::with(['order'])->get());
    }

    public function store(StoreCreditNoteRequest $request)
    {
        $credit_note_log = [];
		$credit_note = [];		
		
		$cust = Order::select('customer_id')->where('id', $request->order_id)->first();
		
		$credit_note = $request->all();
		
		$credit_note['customer_id'] = $cust->customer_id;
		
		$creditNote = CreditNote::create($credit_note);		

		$customer = Customer::find($cust->customer_id);

		if($customer->credit_note_balance == null){
			Customer::where('id', $cust->customer_id)
				   ->update([
					   'credit_note_balance' => $creditNote->amount
					]);
		}else{
			$customer->increment('credit_note_balance', $creditNote->amount);
		}		
		
		$credit_note_log['credit_order_id'] = $creditNote->order_id;
		$credit_note_log['customer_id'] = $cust->customer_id;
		$credit_note_log['amount'] = $creditNote->amount;
		$credit_note_log['balance'] = $creditNote->amount;		
		
		CreditNoteLog::insert($credit_note_log);

        return (new CreditNoteResource($creditNote))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CreditNote $creditNote)
    {
        abort_if(Gate::denies('credit_note_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CreditNoteResource($creditNote->load(['order']));
    }

    public function update(UpdateCreditNoteRequest $request, CreditNote $creditNote)
    {
        $creditNote->update($request->all());

        return (new CreditNoteResource($creditNote))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CreditNote $creditNote)
    {
        abort_if(Gate::denies('credit_note_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $creditNote->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
