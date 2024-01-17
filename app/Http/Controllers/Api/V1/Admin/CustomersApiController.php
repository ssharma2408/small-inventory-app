<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\Admin\CustomerResource;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomersApiController extends Controller
{
    use ApiHelpers;

    public function uploaddata(Request $request)
    {
        if ($request->hasFile('upload_image')) {
            $file = $request->file('upload_image');
            $extension  = $file->getClientOriginalExtension();
            $name = time() . '_' . str_replace(" ", "_", $request->name) . '.' . $extension;
            Storage::disk('do')->put(
                '/' . $_ENV['DO_FOLDER'] . '/' . $name,
                file_get_contents($request->file('upload_image')->getRealPath()),
                'public'
            );

            $product_detail['image_url'] = $name;

            return (new CustomerResource($product_detail))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        }
    }

    public function index()
    {
        $payment_arr = [];
		abort_if($this->can('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customers = Customer::all();
		
		$payments = DB::table('customers')
				->select('order_payment_master.order_total', 'order_payment_master.order_paid', 'order_payment_master.order_pending', 'customers.id')
				->join('order_payment_master','order_payment_master.customer_id','=','customers.id')
				->get()->toArray();

		
		foreach($payments as $pay){
			$pay = (array) $pay;			
			if(!array_key_exists($pay['id'], $payment_arr)) {				
				$payment_arr[$pay['id']] = 0;
			}
			
			$payment_arr[$pay['id']] += $pay['order_total'];
		}
		
		return response()->json([
            'customers' => $customers,
            'payment_arr'=> $payment_arr
        ], 200);
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
       // abort_if($this->can('customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $unpaid = 0;
		$paid = 0;
		$status = [];
		$customers = [];
		$orders = [];
		$user = \Auth::user();
		$status_code = 401;
		$role = $user->roles()->first()->toArray();
		if ($role['title'] == 'Sales Manager') {
			$status_code = 200;
			$status = ['Due', 'Closed',  'Overdue'];
			$customers = Customer::where('id',$customer->id)->first();
			$unpaid = Order::where('customer_id', $customer->id)->where('status',4)->sum('order_total');
			$paid = Order::where('customer_id', $customer->id)->whereIn('status',[1,3])->sum('order_total');
			$orders = Order::where('customer_id', $customer->id)->with(['sales_manager', 'customer', 'payment'])->orderBy('id', 'desc')->get();
		}
		return response()->json([
			'unpaid' => $unpaid,
			'paid' => $paid,
            'total_order' => $unpaid + $paid,
			'orders' => $orders,
			'status' => $status,
			'customers' => $customers,
		], $status_code);

      /*  return new CustomerResource($customer);*/
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
	
	public function revenue($customer_id){
		if($customer_id ==""){
			return;
		}
		
		$payments = DB::table('customers')
				->select('order_payment_master.order_number', 'order_payment_master.order_total', 'order_payment_master.order_paid', 'order_payment_master.order_pending', 'customers.name', 'customers.phone_number', 'customers.email')
				->join('order_payment_master','order_payment_master.customer_id','=','customers.id')
				->where('order_payment_master.customer_id','=',$customer_id)->get()->toArray();
		return new CustomerResource($payments);
		
	}
}
