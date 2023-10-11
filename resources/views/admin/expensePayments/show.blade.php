@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.expensePayment.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.expense-payments.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.expensePayment.fields.id') }}
                        </th>
                        <td>
                            {{ $expensePayment->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.expensePayment.fields.expense') }}
                        </th>
                        <td>
                            {{ $expensePayment->expense->stock ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.expensePayment.fields.payment') }}
                        </th>
                        <td>
                            {{ $expensePayment->payment->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.expensePayment.fields.amount') }}
                        </th>
                        <td>
                            {{ $expensePayment->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.expensePayment.fields.description') }}
                        </th>
                        <td>
                            {{ $expensePayment->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.expensePayment.fields.date') }}
                        </th>
                        <td>
                            {{ $expensePayment->date }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.expense-payments.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection