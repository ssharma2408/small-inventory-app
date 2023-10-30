@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
				<div class="card-header row">
                    <div class="col-lg-4">Module</div>
                    <div class="col-lg-4">Total</div>
                    <div class="col-lg-4">Link</div>
                </div>
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
            </div>
        </div>
	</div>
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
</div>
@endsection
@section('scripts')
@parent

@endsection