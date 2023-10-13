<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTaxRequest;
use App\Http\Requests\StoreTaxRequest;
use App\Http\Requests\UpdateTaxRequest;
use App\Models\Tax;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tax_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $taxes = Tax::all();

        return view('admin.taxes.index', compact('taxes'));
    }

    public function create()
    {
        abort_if(Gate::denies('tax_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.taxes.create');
    }

    public function store(StoreTaxRequest $request)
    {
        $tax = Tax::create($request->all());

        return redirect()->route('admin.taxes.index');
    }

    public function edit(Tax $tax)
    {
        abort_if(Gate::denies('tax_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.taxes.edit', compact('tax'));
    }

    public function update(UpdateTaxRequest $request, Tax $tax)
    {
        $tax->update($request->all());

        return redirect()->route('admin.taxes.index');
    }

    public function show(Tax $tax)
    {
        abort_if(Gate::denies('tax_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.taxes.show', compact('tax'));
    }

    public function destroy(Tax $tax)
    {
        abort_if(Gate::denies('tax_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tax->delete();

        return back();
    }

    public function massDestroy(MassDestroyTaxRequest $request)
    {
        $taxes = Tax::find(request('ids'));

        foreach ($taxes as $tax) {
            $tax->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function get_tax($tax_id){
		
		if($tax_id == ""){
			return false;
		}
		$tax = Tax::select('tax')->where('id', $tax_id)->first();
		
		return response()->json(array('success'=>1, 'tax'=>$tax), 200);
	}
}
