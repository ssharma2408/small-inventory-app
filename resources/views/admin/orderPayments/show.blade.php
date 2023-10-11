@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.orderPayment.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.order-payments.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.orderPayment.fields.id') }}
                        </th>
                        <td>
                            {{ $orderPayment->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderPayment.fields.order') }}
                        </th>
                        <td>
                            {{ $orderPayment->order->order_total ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderPayment.fields.payment') }}
                        </th>
                        <td>
                            {{ $orderPayment->payment->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderPayment.fields.amount') }}
                        </th>
                        <td>
                            {{ $orderPayment->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderPayment.fields.description') }}
                        </th>
                        <td>
                            {{ $orderPayment->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderPayment.fields.date') }}
                        </th>
                        <td>
                            {{ $orderPayment->date }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.order-payments.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection