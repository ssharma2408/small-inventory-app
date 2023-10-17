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
                            {{ trans('cruds.category.fields.category') }}
                        </th>
                        <td>
                            {{ $inventory->category->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.inventory.fields.product') }}
                        </th>
                        <td>
                            {{ $inventory->product->name ?? '' }}
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
                            {{ trans('cruds.inventory.fields.box_or_unit') }}
                        </th>
                        <td>
                            {{ App\Models\Inventory::BOX_OR_UNIT_RADIO[$inventory->box_or_unit] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.inventory.fields.stock') }}
                        </th>
                        <td>
                            {{ $inventory->stock }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.inventory.fields.purchase_price') }}
                        </th>
                        <td>
                            {{ $inventory->purchase_price }}
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
                            {{ trans('cruds.inventory.fields.tax') }}
                        </th>
                        <td>
                            {{ $inventory->tax->title ?? '' }}
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
                            <img width = "100" height="100" src="{{ $_ENV['DO_CDN_URL'].$inventory->image_url }}">
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