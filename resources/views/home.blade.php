@extends('layouts.admin')
@section('content')
<div class="content">
	@if(\Auth::user()->roles()->first()->title != "Delivery Agent")

	@if(\Auth::user()->roles()->first()->title != "Sales Manager")
	<div class="row">
		<div class="col-lg-12">
			<h4 class="mb-4">Total Open Orders: {{$admin['total_open_order']}}</h4>
			@if(!empty($admin['accepted_order']))
			<div class="row">
				<div class="col-md-6">
					<div class="card card-outline card-info">
						<div class="card-header">
							<h3 class="card-title">Last 3 Accepted Order:</h3>
						</div>
						<div class="card-body p-0">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Order ID</th>
										<th>Order Amount</th>
									</tr>
								</thead>
								<tbody>
									@foreach($admin['accepted_order'] as $ord)
									<tr>
										<td>{{$ord['id']}}</td>
										<td>{{$ord['order_total']}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							@endif
						</div>
					</div>
				</div>
				@if(!empty($admin['expenses']))
				<div class="col-md-6">
					<div class="card card-outline card-info">
						<div class="card-header">
							<h3 class="card-title">Last 3 Expense:</h3>
						</div>
						<div class="card-body p-0">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Invoice Number</th>
										<th>Amount</th>
									</tr>
								</thead>
								<tbody>
									@foreach($admin['expenses'] as $exp)
									<tr>
										<td>{{$exp['invoice_number']}}</td>
										<td>{{$exp['final_price']}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@else
	<div class="row">
		<div class="col-lg-12">
			<h4 class="mb-4">Sales Dashboard</h4>
			@if(!empty($sales['accepted_order']))
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Last 3 Accepted Order:</h3>
						</div>
						<div class="card-body p-0">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Order ID</th>
										<th>Order Amount</th>
									</tr>
								</thead>
								<tbody>
									@foreach($sales['accepted_order'] as $ord)
									<tr>
										<td>{{$ord['id']}}</td>
										<td>{{$ord['order_total']}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif

	@if(\Auth::user()->roles()->first()->title != "Sales Manager")

	<h4 class="mb-4">Reports</h4>
	<div class="row">
		<div class="col-12">
			<div class="card">
				
				<div class="card-body p-0">
					<table class="table table-striped">
						
						<tbody>
						
							<tr>
								<td>Purchase Report</td>
								<td class="bg-success"><a href="#">Paid</a></td>
								<td class="bg-warning"><a href="#">Un Paid</a></td>
								<td class="bg-danger"><a href="#">Overdue</a></td>
							</tr>
							<tr>
								<td>Order Report</td>
								<td class="bg-success"><a href="#">Paid</a></td>
								<td class="bg-warning"><a href="#">Un Paid</a></td>
								<td class="bg-danger"><a href="#">Overdue</a></td>
							</tr>
						
						</tbody>
					</table>
					
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

</div>
@endsection
@section('scripts')
@parent

@endsection