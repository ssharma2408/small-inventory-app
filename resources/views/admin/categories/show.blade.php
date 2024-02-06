@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.category.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.id') }}
                        </th>
                        <td>
                            {{ $category->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.name') }}
                        </th>
                        <td>
                            {{ $category->name }}
                        </td>
                    </tr>
					<tr>
                        <th>
                            {{ trans('cruds.category.fields.category') }}
                        </th>
                        <td>
                            {{ $category->category->name ?? '' }}
                        </td>
                    </tr>
					<tr>
                        <th>
                            {{ trans('cruds.category.fields.category_image') }}
                        </th>
                        <td>
                            <img width = "100" height="100" src="{{ $_ENV['DO_CDN_URL'].$category->image_url }}">
                        </td>
                    </tr>
					<tr>
                        <th>
                            {{ trans('cruds.category.fields.show_fe') }}
                        </th>
                        <td>
                            {{ $category->show_fe == 1 ? 'Yes' : 'No' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection