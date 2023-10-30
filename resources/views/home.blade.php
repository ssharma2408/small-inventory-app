@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Category
                </div>

                <div class="card-body">
                    Total <b>{{$category['total']}}</b>
					
					@if(!empty($category['latest']))
						<ul>
						@foreach($category['latest'] as $cat)
							<li>{{$cat['name']}}</li>
						@endforeach
						</ul>
					@endif
					
					<a href="{{route('admin.categories.index')}}">View All</a>
                </div>
            </div>
        </div>
		<div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Product
                </div>

                <div class="card-body">
                   Total <b>{{$product['total']}}</b>
					
					@if(!empty($product['latest']))
						<ul>
						@foreach($product['latest'] as $prod)
							<li>{{$prod['name']}}</li>
						@endforeach
						</ul>
					@endif
					
					<a href="{{route('admin.products.index')}}">View All</a>
                </div>
            </div>
        </div>
		<div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Tax
                </div>

                <div class="card-body">
                    Total <b>{{$tax['total']}}</b>
					
					@if(!empty($tax['latest']))
						<ul>
						@foreach($tax['latest'] as $t)
							<li>{{$t['title']}}</li>
						@endforeach
						</ul>
					@endif
					
					<a href="{{route('admin.taxes.index')}}">View All</a>
                </div>
            </div>
        </div>
		<div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Payment Method
                </div>

                <div class="card-body">
                    Total <b>{{$payment_method['total']}}</b>
					
					@if(!empty($payment_method['latest']))
						<ul>
						@foreach($payment_method['latest'] as $pm)
							<li>{{$pm['name']}}</li>
						@endforeach
						</ul>
					@endif
					
					<a href="{{route('admin.payment-methods.index')}}">View All</a>
                </div>
            </div>
        </div>
		<div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Shrinkage
                </div>

                <div class="card-body">
                    Total <b>{{$shrinkage['total']}}</b>
					
					@if(!empty($shrinkage['latest']))
						<ul>
						@foreach($shrinkage['latest'] as $shr)
							<li>{{date('j F, Y', strtotime($shr['date']))}}</li>
						@endforeach
						</ul>
					@endif
					
					<a href="{{route('admin.shrinkages.index')}}">View All</a>
                </div>
            </div>
        </div>
		<div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Supplier
                </div>

                <div class="card-body">
                    Total <b>{{$supplier['total']}}</b>
					
					@if(!empty($supplier['latest']))
						<ul>
						@foreach($supplier['latest'] as $sup)
							<li>{{$sup['supplier_name']}}</li>
						@endforeach
						</ul>
					@endif
					
					<a href="{{route('admin.suppliers.index')}}">View All</a>
                </div>
            </div>
        </div>
		<div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Expenses
                </div>

                <div class="card-body">
                    Total <b>{{$expense['total']}}</b>
					
					@if(!empty($expense['latest']))
						<ul>
						@foreach($expense['latest'] as $exp)
							<li>{{$exp['invoice_number']}}</li>
						@endforeach
						</ul>
					@endif
					
					<a href="{{route('admin.inventories.index')}}">View All</a>
                </div>
            </div>
        </div>
		<div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Customers
                </div>

                <div class="card-body">
                    Total <b>{{$customer['total']}}</b>
					
					@if(!empty($customer['latest']))
						<ul>
						@foreach($customer['latest'] as $cust)
							<li>{{$cust['name']}}</li>
						@endforeach
						</ul>
					@endif
					
					<a href="{{route('admin.customers.index')}}">View All</a>
                </div>
            </div>
        </div>
		<div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Orders
                </div>

                <div class="card-body">
                    Total <b>{{$order['total']}}</b>
					
					@if(!empty($order['latest']))
						<ul>
						@foreach($order['latest'] as $ord)
							<li>{{date('j F, Y', strtotime($ord['created_at']))}}</li>
						@endforeach
						</ul>
					@endif
					
					<a href="{{route('admin.orders.index')}}">View All</a>
                </div>
            </div>
        </div>
		<div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Reports
                </div>

                <div class="card-body">
                   Total <b>2</b>					
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