@extends('layouts.admin')
@section('content')
@can('order_payment_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.order-payments.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.orderPayment.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.orderPayment.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-OrderPayment">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.orderPayment.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.orderPayment.fields.order') }}
                        </th>
                        <th>
                            {{ trans('cruds.orderPayment.fields.payment') }}
                        </th>
                        <th>
                            {{ trans('cruds.orderPayment.fields.amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.orderPayment.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.orderPayment.fields.date') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderPayments as $key => $orderPayment)
                        <tr data-entry-id="{{ $orderPayment->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $orderPayment->id ?? '' }}
                            </td>
                            <td>
                                {{ $orderPayment->order->order_total ?? '' }}
                            </td>
                            <td>
                                {{ $orderPayment->payment->name ?? '' }}
                            </td>
                            <td>
                                {{ $orderPayment->amount ?? '' }}
                            </td>
                            <td>
                                {{ $orderPayment->description ?? '' }}
                            </td>
                            <td>
                                {{ $orderPayment->date ?? '' }}
                            </td>
                            <td>
                                @can('order_payment_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.order-payments.show', $orderPayment->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('order_payment_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.order-payments.edit', $orderPayment->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('order_payment_delete')
                                    <form action="{{ route('admin.order-payments.destroy', $orderPayment->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('order_payment_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.order-payments.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-OrderPayment:not(.ajaxTable)').DataTable({ buttons: [] })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection