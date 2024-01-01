@extends('layouts.admin')
@section('content')
@can('credit_note_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.credit-notes.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.creditNote.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.creditNote.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-CreditNote">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.creditNote.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.creditNote.fields.order') }}
                        </th>
                        <th>
                            {{ trans('cruds.creditNote.fields.amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.creditNote.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.creditNote.fields.date') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($creditNotes as $key => $creditNote)
                        <tr data-entry-id="{{ $creditNote->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $creditNote->id ?? '' }}
                            </td>
                            <td>
                                {{ $creditNote->order->order_total ?? '' }}
                            </td>
                            <td>
                                {{ $creditNote->amount ?? '' }}
                            </td>
                            <td>
                                {{ $creditNote->description ?? '' }}
                            </td>
                            <td>
                                {{ $creditNote->date ?? '' }}
                            </td>
                            <td>
                                @can('credit_note_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.credit-notes.show', $creditNote->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('credit_note_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.credit-notes.edit', $creditNote->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('credit_note_delete')
                                    <form action="{{ route('admin.credit-notes.destroy', $creditNote->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('credit_note_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.credit-notes.massDestroy') }}",
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
  let table = $('.datatable-CreditNote:not(.ajaxTable)').DataTable({ buttons: [] })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection