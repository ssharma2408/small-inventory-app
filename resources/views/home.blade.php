@extends('layouts.admin')
@section('content')
<div class="content">
    @if(\Auth::user()->roles()->first()->title != "Delivery Agent")
		
		@if(\Auth::user()->roles()->first()->title != "Sales Manager")
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header">
							Admin Dashboard
						</div>
						<div class="card-body">
							<div><h5>Total Open Orders: {{$admin['total_open_order']}}</h5></div>
							@if(!empty($admin['accepted_order']))
								<div class="mt-2 mb-2"><h5>Last 3 Accepted Order:</h5> 
									<div class="row">
										<div class="col-lg-6"><b>Order ID</b></div>
										<div class="col-lg-6"><b>Order Amount</b></div>
									</div>								
									@foreach($admin['accepted_order'] as $ord)
										<div class="row">
											<div class="col-lg-6">{{$ord['id']}}</div>
											<div class="col-lg-6">{{$ord['order_total']}}</div>
										</div>
									@endforeach
								</div>
							@endif
							@if(!empty($admin['expenses']))
								<div><h5>Last 3 Expense:</h5>
									<div class="row">
										<div class="col-lg-6"><b>Invoice Number</b></div>
										<div class="col-lg-6"><b>Amount</b></div>
									</div>
									@foreach($admin['expenses'] as $exp)
										<div class="row">
											<div class="col-lg-6">{{$exp['invoice_number']}}</div>
											<div class="col-lg-6">{{$exp['final_price']}}</div>
										</div>
									@endforeach									
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		@else
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header">
							Sales Dashboard
						</div>
						<div class="card-body">
							@if(!empty($sales['accepted_order']))
								<div><h5>Last 3 Accepted Order:</h5> 
									<div class="row">
										<div class="col-lg-6"><b>Order ID</b></div>
										<div class="col-lg-6"><b>Order Amount</b></div>
									</div>								
									@foreach($sales['accepted_order'] as $ord)
										<div class="row">
											<div class="col-lg-6">{{$ord['id']}}</div>
											<div class="col-lg-6">{{$ord['order_total']}}</div>
										</div>
									@endforeach
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		@endif
	
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header row">
						<div class="col-lg-4">Module</div>
						<div class="col-lg-4">Total</div>
						<div class="col-lg-4">Link</div>
					</div>
					@if(\Auth::user()->roles()->first()->title != "Sales Manager")
						<div class="card-body row">
							<div class="col-lg-4">Category</div>
							<div class="col-lg-4">{{$category['total']}}</div>
							<div class="col-lg-4"><a href="{{route('admin.categories.index')}}">View All</a></div>
						</div>
						<div class="card-body row">
							<div class="col-lg-4">Product</div>
							<div class="col-lg-4">{{$product['total']}}</div>
							<div class="col-lg-4"><a href="{{route('admin.products.index')}}">View All</a></div>
						</div>
						<div class="card-body row">
							<div class="col-lg-4">Tax</div>
							<div class="col-lg-4">{{$tax['total']}}</div>
							<div class="col-lg-4"><a href="{{route('admin.taxes.index')}}">View All</a></div>
						</div>
						<div class="card-body row">
							<div class="col-lg-4">Payment Method</div>
							<div class="col-lg-4">{{$payment_method['total']}}</div>
							<div class="col-lg-4"><a href="{{route('admin.payment-methods.index')}}">View All</a></div>
						</div>
						<div class="card-body row">
							<div class="col-lg-4">Shrinkage</div>
							<div class="col-lg-4">{{$shrinkage['total']}}</div>
							<div class="col-lg-4"><a href="{{route('admin.shrinkages.index')}}">View All</a></div>
						</div>
						<div class="card-body row">
							<div class="col-lg-4">Supplier</div>
							<div class="col-lg-4">{{$supplier['total']}}</div>
							<div class="col-lg-4"><a href="{{route('admin.suppliers.index')}}">View All</a></div>
						</div>
						<div class="card-body row">
							<div class="col-lg-4">Expenses</div>
							<div class="col-lg-4">{{$expense['total']}}</div>
							<div class="col-lg-4"><a href="{{route('admin.inventories.index')}}">View All</a></div>
						</div>
						<div class="card-body row">
							<div class="col-lg-4">Customers</div>
							<div class="col-lg-4">{{$customer['total']}}</div>
							<div class="col-lg-4"><a href="{{route('admin.customers.index')}}">View All</a></div>
						</div>
						<div class="card-body row">
							<div class="col-lg-4">Orders</div>
							<div class="col-lg-4">{{$order['total']}}</div>
							<div class="col-lg-4"><a href="{{route('admin.orders.index')}}">View All</a></div>
						</div>
					@else
					<div class="card-body row">
						<div class="col-lg-4">Customers</div>
						<div class="col-lg-4">{{$customer['total']}}</div>
						<div class="col-lg-4"><a href="{{route('admin.customers.index')}}">View All</a></div>
					</div>	
					<div class="card-body row">
						<div class="col-lg-4">Supplier</div>
						<div class="col-lg-4">{{$supplier['total']}}</div>
						<div class="col-lg-4"><a href="{{route('admin.suppliers.index')}}">View All</a></div>
					</div>
					<div class="card-body row">
						<div class="col-lg-4">Product</div>
						<div class="col-lg-4">{{$product['total']}}</div>
						<div class="col-lg-4"><a href="{{route('admin.products.index')}}">View All</a></div>
					</div>
					@endif
				</div>
			</div>
		</div>
		@if(\Auth::user()->roles()->first()->title != "Sales Manager")
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header">
							Reports
						</div>
						<div class="card-body">
							<ul>						
								<li><a href="{{ route('admin.reports.get_expense_report') }}">Purchase Report</a></li>
								<li><a href="{{ route('admin.reports.get_order_report') }}">Order Report</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		@endif
	@else
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						Dashboard
					</div>
					<div class="card-body">
						<div><h5>Total Open Orders: {{$del_agent['total_assigned_orders']}}</h5></div>
					</div>
				</div>
			</div>
		</div>
	@endif
</div>
@endsection
@section('scripts')
@parent

@endsection