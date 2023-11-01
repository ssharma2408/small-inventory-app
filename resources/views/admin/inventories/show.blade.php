@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.inventory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.inventories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.inventory.fields.id') }}
                        </th>
                        <td>
                            {{ $inventory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.inventory.fields.supplier') }}
                        </th>
                        <td>
                            {{ $inventory->supplier->supplier_name ?? '' }}
                        </td>
                    </tr>                    
					<tr>
                        <th>
                            {{ trans('cruds.inventory.fields.invoice_number') }}
                        </th>
                        <td>
                            {{ $inventory->invoice_number }}
                        </td>
                    </tr>
					<tr>
						<th>
							Expense Items
						</th>
						<td>
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<th>
											Category Name
										</th>
										<th>
											Sub Category Name
										</th>
										<th>
											Product Name
										</th>
										<th>
											Box or unit
										</th>
										<th>
											Stock
										</th>
										<th>
											Purchase Price
										</th>											
										<th>
											Tax
										</th>
										<th>
											Amount
										</th>										
									</tr>
									@foreach($expense_items as $item)
										<tr>
											<td>
											{{ $item->category_name }}
											</td>
											<td>
											{{ $item->sub_category_name }}
											</td>
											<td>
											{{ $item->name }}
											</td>
											<td>
												@if($item->is_box)
													Box
												@else
													Unit
												@endif
											</td>											
											<td>
											{{ $item->stock }}
											</td>
											<td>
											{{ $item->purchase_price }}
											</td>											
											<td>
											{{ $item->title }}
											</td>
											<td>
												@php
													$qty = $item->stock;													
													$amount = $qty * $item->purchase_price;
													
													$total = $amount + (($amount * $item->tax)/100);
												@endphp
												{{ $total }}
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
                        <th>
                            {{ trans('cruds.inventory.fields.expense_total') }}
                        </th>
                        <td>
                            {{ $inventory->expense_total ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.inventory.fields.expense_tax') }}
                        </th>
                        <td>
                            {{ $inventory->expense_tax  ?? ''  }}
                        </td>
                    </tr>   
                    <tr>
                        <th>
                            {{ trans('cruds.inventory.fields.discount_type') }}
                        </th>
                        <td>
                            {{ App\Models\Inventory::DISCOUNT_TYPE_RADIO[$inventory->discount_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.inventory.fields.discount') }}
                        </th>
                        <td>
                            {{ $inventory->discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.inventory.fields.final_price') }}
                        </th>
                        <td>
                            {{ $inventory->final_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.inventory.fields.days_payable_outstanding') }}
                        </th>
                        <td>
                            {{ App\Models\Inventory::DAYS_PAYABLE_OUTSTANDING_SELECT[$inventory->days_payable_outstanding] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.inventory.fields.po_file') }}
                        </th>
                        <td>
                            @if($inventory->image_url != "")
								<img width = "100" height="100" src="{{ $_ENV['DO_CDN_URL'].$inventory->image_url }}">
							@endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.inventories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection