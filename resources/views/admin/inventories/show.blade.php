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
                            {{ trans('cruds.inventory.fields.product_name') }}
                        </th>
                        <td>
                            {{ $inventory->product_name }}
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
                            {{ trans('cruds.inventory.fields.price') }}
                        </th>
                        <td>
                            {{ $inventory->price }}
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
                            {{ $inventory->tax }}
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