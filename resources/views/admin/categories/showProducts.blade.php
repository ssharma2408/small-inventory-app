@extends('layouts.admin')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Category Produts</h4>
                </div>
                <div class="card-body">
                @if(count($products)>0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="30px">#</th>
                                <th>Title</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyContents">
                            @foreach ($products as $product)
                            <tr class="tableRow" data-id="{{ $product->id }}">
                                <td class="text-center">&#9776;</td>
                                <td>{{ $product->name }}</td>
                                <td><img width = "70" height="50" src="{{ $_ENV['DO_CDN_URL'].$product->image_url }}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                <h4 style="text-align: center;">No data found</h4>
                @endif
                </div>
            </div>
        </div>
    </div>
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