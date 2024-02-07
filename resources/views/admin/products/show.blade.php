@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.product.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.id') }}
                        </th>
                        <td>
                            {{ $product->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.name') }}
                        </th>
                        <td>
                            {{ $product->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.category') }}
                        </th>
                        <td>
                            {{ $product->category->name }}
                        </td>
                    </tr>
					<tr>
                        <th>
                            {{ trans('cruds.category.fields.sub_category') }}
                        </th>
                        <td>
                            {{ $product->sub_category->name ?? '' }}
                        </td>
                    </tr>
					<tr>
                        <th>
                            {{ trans('cruds.product.fields.maximum_selling_price') }}
                        </th>
                        <td>
                            {{ $product->maximum_selling_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.selling_price') }}
                        </th>
                        <td>
                            {{ $product->selling_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.stock') }}
                        </th>
                        <td>
                            {{ $product->stock }}
                        </td>
                    </tr>
					<tr>
                        <th>
                            {{ trans('cruds.product.fields.tax') }}
                        </th>
                        <td>
                            {{ $product->tax->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.product_image') }}
                        </th>
                        <td>
                            <img width = "100" height="100" src="{{ $_ENV['DO_CDN_URL'].$product->image_url }}">
                        </td>
                    </tr>
					
					<tr>
                        <th>
                            {{ trans('cruds.product.fields.description_website') }}
                        </th>
                        <td>
                            {{ $product->description_website ?? '' }}
                        </td>
                    </tr>
					
					<tr>
                        <th>
                            {{ trans('cruds.product.fields.description_invoice') }}
                        </th>
                        <td>
                           {{ $product->description_invoice ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.product.fields.box_size') }}
                        </th>
                        <td>
                            {{ $product->box_size }}
                        </td>
                    </tr>
					<tr>
                        <th>
                            {{ trans('cruds.product.fields.show_fe') }}
                        </th>
                        <td>
                            {{ $product->show_fe == 1 ? 'Yes' : 'No' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection