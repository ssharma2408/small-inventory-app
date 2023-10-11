@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.shrinkage.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shrinkages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.shrinkage.fields.id') }}
                        </th>
                        <td>
                            {{ $shrinkage->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shrinkage.fields.product') }}
                        </th>
                        <td>
                            {{ $shrinkage->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shrinkage.fields.number') }}
                        </th>
                        <td>
                            {{ $shrinkage->number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shrinkage.fields.date') }}
                        </th>
                        <td>
                            {{ $shrinkage->date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shrinkage.fields.description') }}
                        </th>
                        <td>
                            {!! $shrinkage->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shrinkage.fields.added_by') }}
                        </th>
                        <td>
                            {{ $shrinkage->added_by->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shrinkages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection