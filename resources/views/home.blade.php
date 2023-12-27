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
					<div>
						<h5>Total Open Orders: {{$admin['total_open_order']}}</h5>
					</div>
					@if(!empty($admin['accepted_order']))
					<div class="mt-2 mb-2">
						<h5>Last 3 Accepted Order:</h5>
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
					<div>
						<h5>Last 3 Expense:</h5>
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
					<div>
						<h5>Last 3 Accepted Order:</h5>
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

	@if(\Auth::user()->roles()->first()->title != "Sales Manager")
	<h4 class="mb-4">Modules</h4>
	<div class="row">
		<div class="col-lg-3 col-6">
			<div class="small-box bg-info">
				<div class="inner">
					<h3>16</h3>
					<p>Category</p>
				</div>
				<a href="{{route('admin.categories.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-6">
			<div class="small-box bg-success">
				<div class="inner">
					<h3>{{$product['total']}}</h3>
					<p>Product</p>
				</div>
				<a href="{{route('admin.products.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-6">
			<div class="small-box bg-warning">
				<div class="inner">
					<h3>{{$tax['total']}}</h3>
					<p>Tax</p>
				</div>
				<a href="{{route('admin.taxes.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-6">
			<div class="small-box bg-danger">
				<div class="inner">
					<h3>{{$payment_method['total']}}</h3>
					<p>Payment Method</p>
				</div>
				<a href="{{route('admin.payment-methods.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-6">
			<div class="small-box bg-danger">
				<div class="inner">
					<h3>{{$shrinkage['total']}}</h3>
					<p>Shrinkage</p>
				</div>
				<a href="{{route('admin.shrinkages.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-6">
			<div class="small-box bg-gray">
				<div class="inner">
					<h3>{{$supplier['total']}}</h3>
					<p>Supplier</p>
				</div>
				<a href="{{route('admin.suppliers.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-6">
			<div class="small-box bg-secondary">
				<div class="inner">
					<h3>{{$expense['total']}}</h3>
					<p>Expenses</p>
				</div>
				<a href="{{route('admin.suppliers.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-6">
			<div class="small-box bg-info">
				<div class="inner">
					<h3>{{$customer['total']}}</h3>
					<p>Customers</p>
				</div>
				<a href="{{route('admin.customers.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-6">
			<div class="small-box bg-primary">
				<div class="inner">
					<h3>{{$order['total']}}</h3>
					<p>Orders</p>
				</div>
				<a href="{{route('admin.orders.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		@else
		<div class="col-lg-3 col-6">
			<div class="small-box bg-success">
				<div class="inner">
					<h3>{{$customer['total']}}</h3>
					<p>Customers</p>
				</div>
				<a href="{{route('admin.customers.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-6">
			<div class="small-box bg-warning">
				<div class="inner">
					<h3>{{$supplier['total']}}</h3>
					<p>Supplier</p>
				</div>
				<a href="{{route('admin.suppliers.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-6">
			<div class="small-box bg-danger">
				<div class="inner">
					<h3>{{$product['total']}}</h3>
					<p>Product</p>
				</div>
				<a href="{{route('admin.products.index')}}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
		@endif
	</div>

	@if(\Auth::user()->roles()->first()->title != "Sales Manager")
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<h4 class="mb-0">Reports</h4>
				</div>
				<div class="card-footer p-0">
					<ul class="nav flex-column">
						<li class="nav-item"><a href="{{ route('admin.reports.get_expense_report') }}" class="nav-link">Purchase Report</a></li>
						<li class="nav-item"><a href="{{ route('admin.reports.get_order_report') }}" class="nav-link">Order Report</a></li>
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
					<div>
						<h5>Total Open Orders: {{$del_agent['total_assigned_orders']}}</h5>
					</div>
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