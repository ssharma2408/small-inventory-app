@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.creditNote.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.credit-notes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.creditNote.fields.id') }}
                        </th>
                        <td>
                            {{ $creditNote->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creditNote.fields.order') }}
                        </th>
                        <td>
                            {{ $creditNote->order->order_total ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creditNote.fields.amount') }}
                        </th>
                        <td>
                            {{ $creditNote->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creditNote.fields.description') }}
                        </th>
                        <td>
                            {{ $creditNote->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.creditNote.fields.date') }}
                        </th>
                        <td>
                            {{ $creditNote->date }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.credit-notes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection