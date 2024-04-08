@extends('layouts.admin')
@section('content')


@can('product_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.products.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.product.title_singular') }}
            </a>
        </div>
    </div>
@endcan


<div class="card">
    <div class="card-header">
        Category Produts
    </div>
    @if(count($products)>0)
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Category">
                <thead>
                    <tr>
                        <th width="10">
                            #
                        </th>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody id="tableBodyContents">
                            @foreach ($products as $product)
                            <tr class="tableRow" data-id="{{ $product->id }}">
                                <td class="text-center">&#9776;</td>
                                <td>{{ $product->name }}</td>
                                <td><img width = "70" height="50" src="{{ $_ENV['DO_CDN_URL'].$product->image_url }}"></td>
                                <td>
                                @can('product_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.products.edit', $product->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                </td>
                            </tr>
                            @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
        <h4 style="text-align: center;">No data found</h4>
    @endif
</div>


@endsection

@section('scripts')

    <script type="text/javascript">

        $(document).ready(function () {

            $( "#tableBodyContents" ).sortable({
                items: "tr",
                cursor: 'move',
                opacity: 0.6,
                update: function() {
                    updateOrderPosition();
                }
            });

            function updateOrderPosition() {

                var orderPosition = [];
                var orderPositionIds = [];
                var token = $('meta[name="csrf-token"]').attr('content');

                $('tr.tableRow').each(function(index,element) {
                    orderPosition.push({
                        id: $(this).attr('data-id'),
                        position: index+1
                    });
                    orderPositionIds.push($(this).attr('data-id'));
                });

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('admin/product/reorder') }}",
                    data: {
                        order: orderPosition,
                        ids: orderPositionIds,
                        _token: token
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            console.log(response);
                        } else {
                            console.log(response);
                        }
                    }
                });
            }
        });
    </script>

@endsection