<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\Admin\CustomerResource;
use App\Models\Customer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomersApiController extends Controller
{
    use ApiHelpers;
	
	public function index()
    {       
		abort_if($this->can('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return new CustomerResource(Customer::all());
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->all());

        return (new CustomerResource($customer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Customer $customer)
    {
        abort_if($this->can('customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
        return new CustomerResource($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());

        return (new CustomerResource($customer))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Customer $customer)
    {
        abort_if($this->can('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }	
}
