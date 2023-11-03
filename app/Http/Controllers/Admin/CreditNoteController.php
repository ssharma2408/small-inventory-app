<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCreditNoteRequest;
use App\Http\Requests\StoreCreditNoteRequest;
use App\Http\Requests\UpdateCreditNoteRequest;
use App\Models\CreditNote;
use App\Models\Order;
use App\Models\Customer;
use App\Models\CreditNoteLog;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use DB;

class CreditNoteController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('credit_note_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $creditNotes = CreditNote::with(['order'])->get();

        return view('admin.creditNotes.index', compact('creditNotes'));
    }

    public function create()
    {
        abort_if(Gate::denies('credit_note_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = DB::table('orders')
					->select('orders.id', 'customers.name')
					->join('customers', 'orders.customer_id', '=', 'customers.id')
					->where('orders.deleted_at', null)
					->where('customers.deleted_at', null)
					->get();

        return view('admin.creditNotes.create', compact('orders'));
    }

    public function store(StoreCreditNoteRequest $request)
    {
        $creditNote = CreditNote::create($request->all());
		
		$cust = Order::select('customer_id')->where('id', $creditNote->order_id)->first();

		$customer = Customer::find($cust->customer_id);

		if($customer->credit_note_balance == null){
			Customer::where('id', $cust->customer_id)
				   ->update([
					   'credit_note_balance' => $creditNote->amount
					]);
		}else{
			$customer->increment('credit_note_balance', $creditNote->amount);
		}

		$credit_note_log = [];
		
		$credit_note_log['credit_order_id'] = $creditNote->order_id;
		$credit_note_log['customer_id'] = $cust->customer_id;
		$credit_note_log['amount'] = $creditNote->amount;
		$credit_note_log['balance'] = $creditNote->amount;		
		
		CreditNoteLog::insert($credit_note_log);

        return redirect()->route('admin.credit-notes.index');
    }

    public function edit(CreditNote $creditNote)
    {
        abort_if(Gate::denies('credit_note_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = DB::table('orders')
					->select('orders.id', 'customers.name')
					->join('customers', 'orders.customer_id', '=', 'customers.id')
					->where('orders.deleted_at', null)
					->where('customers.deleted_at', null)
					->get();

        $creditNote->load('order');

        return view('admin.creditNotes.edit', compact('creditNote', 'orders'));
    }

    public function update(UpdateCreditNoteRequest $request, CreditNote $creditNote)
    {
        $creditNote->update($request->all());

        return redirect()->route('admin.credit-notes.index');
    }

    public function show(CreditNote $creditNote)
    {
        abort_if(Gate::denies('credit_note_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $creditNote->load('order');

        return view('admin.creditNotes.show', compact('creditNote'));
    }

    public function destroy(CreditNote $creditNote)
    {
        abort_if(Gate::denies('credit_note_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $creditNote->delete();

        return back();
    }

    public function massDestroy(MassDestroyCreditNoteRequest $request)
    {
        $creditNotes = CreditNote::find(request('ids'));

        foreach ($creditNotes as $creditNote) {
            $creditNote->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
