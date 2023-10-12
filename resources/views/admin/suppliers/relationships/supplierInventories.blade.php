<div class="m-3">
    @can('inventory_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.inventories.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.inventory.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.inventory.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-supplierInventories">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.supplier') }}
                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.product') }}
                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.box_or_unit') }}
                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.stock') }}
                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.purchase_price') }}
                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.discount_type') }}
                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.discount') }}
                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.tax') }}
                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.final_price') }}
                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.days_payable_outstanding') }}
                            </th>
                            <th>
                                {{ trans('cruds.inventory.fields.po_file') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventories as $key => $inventory)
                            <tr data-entry-id="{{ $inventory->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $inventory->id ?? '' }}
                                </td>
                                <td>
                                    {{ $inventory->supplier->supplier_name ?? '' }}
                                </td>
                                <td>
                                    {{ $inventory->product->name ?? '' }}
                                </td>
                                <td>
                                    {{ App\Models\Inventory::BOX_OR_UNIT_RADIO[$inventory->box_or_unit] ?? '' }}
                                </td>
                                <td>
                                    {{ $inventory->stock ?? '' }}
                                </td>
                                <td>
                                    {{ $inventory->purchase_price ?? '' }}
                                </td>
                                <td>
                                    {{ App\Models\Inventory::DISCOUNT_TYPE_RADIO[$inventory->discount_type] ?? '' }}
                                </td>
                                <td>
                                    {{ $inventory->discount ?? '' }}
                                </td>
                                <td>
                                    {{ $inventory->tax->title ?? '' }}
                                </td>
                                <td>
                                    {{ $inventory->final_price ?? '' }}
                                </td>
                                <td>
                                    {{ App\Models\Inventory::DAYS_PAYABLE_OUTSTANDING_SELECT[$inventory->days_payable_outstanding] ?? '' }}
                                </td>
                                <td>
                                    @if($inventory->po_file)
                                        <a href="{{ $inventory->po_file->getUrl() }}" target="_blank">
                                            {{ trans('global.view_file') }}
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @can('inventory_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.inventories.show', $inventory->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('inventory_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.inventories.edit', $inventory->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('inventory_delete')
                                        <form action="{{ route('admin.inventories.destroy', $inventory->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
</div>
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('inventory_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.inventories.massDestroy') }}",
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
  let table = $('.datatable-supplierInventories:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection