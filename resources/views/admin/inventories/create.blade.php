@extends('layouts.admin')
@section('content')
    <?php
    $ddl_html = 'No Category Found';
    
    if (!empty($categories)) {
        $ddl_html = '<select class="category form-control select2" name="item_category[]" required>';
        $ddl_html .= '<option value="" >Select Option</option>';
        foreach ($categories as $cat_id => $val) {
            $ddl_html .= '<option value="' . $cat_id . '">' . $val . '</option>';
        }
        $ddl_html .= '</select>';
    }
    
    $tax_ddl_html = 'No Tax Found';
    
    if (!empty($taxes)) {
        $tax_ddl_html = '<select class="form-control select2 tax_id" name="item_tax_id[]" required>';
        $tax_ddl_html .= '<option value="" >Please Select</option>';
        foreach ($taxes as $tax) {
            $tax_ddl_html .= '<option value="' . $tax['id'] . '">' . $tax['title'] . '</option>';
        }
        $tax_ddl_html .= '</select>';
    }
    
    ?>

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.inventory.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" id="expensefrm" action="{{ route('admin.inventories.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label for="supplier_id">{{ trans('cruds.inventory.fields.supplier') }}</label>
                            <select class="form-control select2 {{ $errors->has('supplier') ? 'is-invalid' : '' }}"
                                name="supplier_id" id="supplier_id">
                                @foreach ($suppliers as $id => $entry)
                                    <option value="{{ $id }}" {{ old('supplier_id') == $id ? 'selected' : '' }}>
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('supplier'))
                                <span class="text-danger">{{ $errors->first('supplier') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.inventory.fields.supplier_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label class="required"
                                for="invoice_number">{{ trans('cruds.inventory.fields.invoice_number') }}</label>
                            <input class="form-control {{ $errors->has('invoice_number') ? 'is-invalid' : '' }}"
                                type="text" name="invoice_number" id="invoice_number"
                                value="{{ old('invoice_number', '') }}" required>
                            @if ($errors->has('invoice_number'))
                                <span class="text-danger">{{ $errors->first('invoice_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.inventory.fields.invoice_number_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.inventory.fields.days_payable_outstanding') }}</label>
                            <select class="form-control {{ $errors->has('days_payable_outstanding') ? 'is-invalid' : '' }}"
                                name="days_payable_outstanding" id="days_payable_outstanding" required>
                                <option value disabled
                                    {{ old('days_payable_outstanding', null) === null ? 'selected' : '' }}>
                                    {{ trans('global.pleaseSelect') }}</option>
                                @foreach (App\Models\Inventory::DAYS_PAYABLE_OUTSTANDING_SELECT as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('days_payable_outstanding', '0') === (string) $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('days_payable_outstanding'))
                                <span class="text-danger">{{ $errors->first('days_payable_outstanding') }}</span>
                            @endif
                            <span
                                class="help-block">{{ trans('cruds.inventory.fields.days_payable_outstanding_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label for="po_file">{{ trans('cruds.inventory.fields.po_file') }}</label>
                            <input class="form-control" type="file" name="po_file" id="po_file" />
                            @if ($errors->has('po_file'))
                                <span class="text-danger">{{ $errors->first('po_file') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.inventory.fields.po_file_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                   
                    <div class="col-lg-4">
                    </div>
                    <div class="col-lg-4">
                    </div>
                </div>


                <div class="form-group">
                    <label class="required" for="order_items">Expense Items</label>
                    <div class="help-block h6">* If you are selecting BOX, then add Box price in Purchase Price<br>* If you
                        are selecting UNIT, then add Unit price in Purchase Price</div>
                    <div class="order-container mb-2">
                        <div class="order-content">
                            <div class="row mb-1">
                                <div class="col-md-3">
                                    <!-- <b>Category Name</b> -->
                                    <b>Category/Sub Category/Product</b>
                                </div>
                                <!-- <div class="col-md-1">
                                    <b>Sub Category Name</b>
                                </div> -->
                                <!-- <div class="col-md-1">
                                    <b>Product Name</b>
                                </div> -->
                                <div class="col-md-1">
                                    <b>Box or unit</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Stock</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Purchase Price</b>
                                </div>
                                <div class="col-md-2">
                                    <b>Tax</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Expiry Date</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Amount</b>
                                </div>
                                <div class="col-md-1">

                                </div>
                            </div>
                            <div class="item_container">
                                <div class="row mb-1 item_row">
                                    <div class="cat_container col-md-3">
                                        <div class="form-group">
                                            <?php
                                            echo $ddl_html;
                                            ?>
                                        </div>
                                        <div class="form-group">
                                        <select class="subcat form-control select2" name="item_subcategory[]">
                                            <option value="">Please select</option>
                                        </select>
                                        </div>
                                        <select
                                            class="order_item form-control select2 {{ $errors->has('product') ? 'is-invalid' : '' }}"
                                            name="item_name[]" required>
                                            <option value="">Please select</option>
                                        </select>
                                        
                                    </div>
                                  
                                    <div class="col-md-1">
                                        <input class="form-check-input cb ml-0" type="checkbox" name="is_box[]" checked />
                                        <label class="form-check-label ml-3">Is Box</label>
                                        <div style="font-size:12px" id="box_size"></div>
                                        <input type="hidden" id="package_val" value="" name="package_val[]" />
                                        <input type="hidden" id="box_or_unit" value="1" name="box_or_unit[]" />
                                    </div>
                                    <div class="col-md-1">
                                        <input class="form-control stock" type="number" name="item_stock[]" />
                                    </div>
                                    <div class="col-md-1">
                                        <input class="form-control price" type="text" name="item_price[]" />
                                    </div>
                                    <div class="col-md-2">
                                        <?php
                                        echo $tax_ddl_html;
                                        ?>
                                        <input type="hidden" class="tax_val" value="" />
                                    </div>
                                    <div class="col-md-1">
                                        <input class="form-control exp_date" type="date" name="item_exp_date[]"
                                            required />
                                    </div>
                                    <div class="col-md-1">
                                        <input class="form-control amount" type="text" name="item_amount[]"
                                            disabled />
                                    </div>
                                    <div class="col-md-1">
                                        <span class="add_row" id="add_row" data-key ="">+</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <label class="required"
                            for="expense_total">{{ trans('cruds.inventory.fields.expense_total') }}</label>
                        <input class="form-control {{ $errors->has('expense_total') ? 'is-invalid' : '' }}" type="number"
                            name="expense_total" id="expense_total" value="{{ old('expense_total', '') }}" step="0.01"
                            required readonly>
                        @if ($errors->has('expense_total'))
                            <span class="text-danger">{{ $errors->first('expense_total') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.inventory.fields.expense_total_helper') }}</span>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <label class="required" for="expense_tax">{{ trans('cruds.inventory.fields.expense_tax') }}</label>
                        <input class="form-control {{ $errors->has('expense_tax') ? 'is-invalid' : '' }}" type="number"
                            name="expense_tax" id="expense_tax" value="{{ old('expense_tax', '') }}" step="0.01"
                            required readonly>
                        @if ($errors->has('expense_tax'))
                            <span class="text-danger">{{ $errors->first('expense_tax') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.inventory.fields.expense_tax_helper') }}</span>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <label class="required">{{ trans('cruds.inventory.fields.discount_type') }}</label>
                        <div class="row">
                            @foreach (App\Models\Inventory::DISCOUNT_TYPE_RADIO as $key => $label)
                                <div class="form-check col-lg-4 ml-3 {{ $errors->has('discount_type') ? 'is-invalid' : '' }}">
                                    <input class="form-check-input" type="radio" id="discount_type_{{ $key }}"
                                        name="discount_type" value="{{ $key }}"
                                        {{ old('discount_type', '0') === (string) $key ? 'checked' : '' }} required>
                                    <label class="form-check-label"
                                        for="discount_type_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                        @if ($errors->has('discount_type'))
                            <span class="text-danger">{{ $errors->first('discount_type') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.inventory.fields.discount_type_helper') }}</span>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <label for="discount">{{ trans('cruds.inventory.fields.discount') }}</label>
                        <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number"
                            name="discount" id="discount" value="{{ old('discount', '0') }}" step="0.01">
                        @if ($errors->has('discount'))
                            <span class="text-danger">{{ $errors->first('discount') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.inventory.fields.discount_helper') }}</span>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <label class="required" for="final_price">{{ trans('cruds.inventory.fields.final_price') }}</label>
                        <input class="form-control {{ $errors->has('final_price') ? 'is-invalid' : '' }}" type="number"
                            name="final_price" id="final_price" value="{{ old('final_price', '') }}" step="0.01"
                            required readonly>
                        @if ($errors->has('final_price'))
                            <span class="text-danger">{{ $errors->first('final_price') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.inventory.fields.final_price_helper') }}</span>
                    </div>
                    
                </div>
                <div class="form-group col-lg-12">
                    <button class="btn btn-danger mr-2" type="submit">
                        {{ trans('global.save') }}
                    </button>
                    <a href="{{url()->previous()}}" class="btn btn-default ">{{ trans('global.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        Dropzone.options.poFileDropzone = {
            url: '{{ route('admin.inventories.storeMedia') }}',
            maxFilesize: 2, // MB
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2
            },
            success: function(file, response) {
                $('form').find('input[name="po_file"]').remove()
                $('form').append('<input type="hidden" name="po_file" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="po_file"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($inventory) && $inventory->po_file)
                    var file = {!! json_encode($inventory->po_file) !!}
                    this.options.addedfile.call(this, file)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="po_file" value="' + file.file_name + '">')
                    this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }

        $(".add_row").click(function() {
            $(this).parent().parent().parent().append(row_html());
        });
        $(".item_container").on('click', '.remove_row', function() {
            $(this).parent().parent().remove();
            calculate_total();
        });

        function row_html() {
            //return '<div class="row mb-3 item_row"><div class="cat_container col-md-2"><div class="form-group"><?php echo $ddl_html; ?></div></div><div class="col-md-1"><select class="subcat form-control select2" name="item_subcategory[]"><option value="">Please select</option></select></div><div class="col-md-1"><select class="order_item form-control select2" name="item_name[]" required><option value="">Please select</option></select></div><div class="col-md-1"><input class="form-check-input cb ml-0" type="checkbox" name="is_box[]" checked /><label class="form-check-label ml-3">Is Box</label><div style="font-size:12px" id="box_size"></div><input type="hidden" id="package_val" value="" name="package_val[]" /><input type="hidden" id="box_or_unit" value="1" name="box_or_unit[]" /></div><div class="col-md-1"><input class="form-control stock" type="number" name="item_stock[]" /></div><div class="col-md-1"><input class="form-control price" type="text" name="item_price[]" /></div><div class="col-md-1"><?php echo $tax_ddl_html; ?><input type="hidden" class="tax_val" value="" /></div><div class="col-md-2"><input class="form-control exp_date" type="date" name="item_exp_date[]" required /></div><div class="col-md-1"><input class="form-control amount" type="text" name="item_amount[]" disabled /></div><div class="col-md-1"><span class="remove_row" id="remove_row" data-key ="">-</span></div></div>';
            return '<div class="row mb-3 mt-3 pt-3 item_row border-top"><div class="cat_container col-md-3"><div class="form-group"><?php echo $ddl_html; ?></div><div class="form-group"> <select class="subcat form-control select2" name="item_subcategory[]"><option value="">Please select</option></select> </div><div class="form-group"><select class="order_item form-control select2" name="item_name[]" required><option value="">Please select</option></select> </div></div><div class="col-md-1"><input class="form-check-input cb ml-0" type="checkbox" name="is_box[]" checked /><label class="form-check-label ml-3">Is Box</label><div style="font-size:12px" id="box_size"></div><input type="hidden" id="package_val" value="" name="package_val[]" /><input type="hidden" id="box_or_unit" value="1" name="box_or_unit[]" /></div><div class="col-md-1"><input class="form-control stock" type="number" name="item_stock[]" /></div><div class="col-md-1"><input class="form-control price" type="text" name="item_price[]" /></div><div class="col-md-2"><?php echo $tax_ddl_html; ?><input type="hidden" class="tax_val" value="" /></div><div class="col-md-1"><input class="form-control exp_date" type="date" name="item_exp_date[]" required /></div><div class="col-md-1"><input class="form-control amount" type="text" name="item_amount[]" disabled /></div><div class="col-md-1"><span class="remove_row" id="remove_row" data-key ="">-</span></div></div>';
        }


        $(document).on("change", ".order_item", function() {
            if ($(this).val() != "") {
              
                var box_size = $(this).parents('.item_row').find("#box_size");
                var package_val = $(this).parents('.item_row').find("#package_val");

                $.ajax({
                    url: 'get_product_detail/' + $(this).val(),
                    type: 'GET',
                    success: function(data) {
                        if (data.success) {
                            box_size.html('Box Size: ' + data.product.box_size);
                            package_val.val(data.product.box_size);
                        }
                    }
                });
            }

        });

        $(document).on("change", ".subcat", function() {
            var cat_id = $(this).parents('.item_row').find('.category').val();
            var pdod_ddl = $(this).parents('.item_row').find('.order_item');
            if (cat_id != "" && $(this).val() != "") {
                populate_products(cat_id, $(this).val(), pdod_ddl);
            }
        });

        $(document).on("change", ".category", function() {

            if ($(this).val() != "") {

                var cat_id = $(this).val();
                var subcat_ddl = $(this).parents('.item_row').find(".subcat");
                var product_ddl = $(this).parents('.item_row').find(".order_item");

                $.ajax({
                    url: '/admin/categories/get_sub_category/' + cat_id,
                    type: 'GET',
                    success: function(data) {
                        if (data.success) {
                            var html = '<option value="">Please select</option>';
                            if (data.subcategories.length > 0) {
                                $.each(data.subcategories, function(key, val) {
                                    html += '<option value="' + val.id + '">' + val.name +
                                        '</option>';
                                });
                            }
                            subcat_ddl.html(html);
                            populate_products(cat_id, 0, product_ddl);
                        }
                    }
                });
            }

        });

        function populate_products(cat_id, sub_cat_id = 0, prod_ddl) {

            $.ajax({
                url: 'get_products/' + cat_id + '/' + sub_cat_id,
                type: 'GET',
                success: function(data) {
                    if (data.success) {
                        var html = '<option value="">Please select</option>';
                        if (data.products.length > 0) {
                            $.each(data.products, function(key, val) {
                                html += '<option value="' + val.id + '">' + val.name + '</option>';
                            });
                        }
                        prod_ddl.html(html);
                    }
                }
            });
        }

        $(document).on("keyup", ".stock, .price", function() {
            populate_amount($(this));
        });

        $(document).on("change", ".cb", function() {

            var box_unit = $(this).parent().find("#box_or_unit");

            if ($(this).is(":checked")) {
                box_unit.val(1);
            } else {
                box_unit.val(0);
            }
            populate_amount($(this));
        });

        $(document).on("change", ".tax_id", function() {
            get_tax_val($(this));
        });


        function get_tax_val(tax_ele) {

            var tax_id = tax_ele.val();
            var tax_val = tax_ele.parent().find(".tax_val");

            if (tax_id != "") {
                $.ajax({
                    url: '/admin/taxes/get_tax/' + tax_id,
                    type: 'GET',
                    success: function(data) {
                        if (data.success) {
                            tax_val.val(data.tax.tax);
                            populate_amount(tax_ele);
                        }
                    }
                });
            }
        }

        function populate_amount(ele) {
            var stock = ele.parent().parent().find(".stock").val();
            var purchase_price = ele.parent().parent().find(".price").val();
            var amount = ele.parent().parent().find(".amount");
            var tax = ele.parent().parent().find(".tax_val").val();

            if (stock != "" && purchase_price != "" && tax != "") {
                var amount_total = 0,
                    total = 0;

                total = stock * purchase_price;

                amount_total = total + ((total * tax) / 100);
                amount.val(amount_total);
                calculate_total();
            }

        }


        $(document).on("change", "#discount_type_0, #discount_type_1", function() {
            calculate_total();
        });

        $(document).on("keyup", "#discount", function() {
            calculate_total();
        });

        function calculate_total() {

            var expense_total = 0,
                final_price = 0,
                discount;
            discount = $("#discount").val();

            $(".item_row").each(function() {
                var stock = $(this).find(".stock").val();
                var price = $(this).find(".price").val();
                var amount = $(this).find(".amount").val();

                expense_total += (stock * price);
                final_price += parseFloat(amount);
            });

            $("#expense_total").val(expense_total);
            $("#expense_tax").val(final_price - expense_total);


            if (discount > 0) {
                if ($("#discount_type_0").is(":checked")) {
                    if (discount < final_price) {
                        final_price = final_price - discount;
                    }
                } else {
                    if (((final_price * discount) / 100) < final_price) {
                        final_price = final_price - (final_price * discount) / 100;
                    }
                }
            }

            $("#final_price").val(final_price);

        }
    </script>
@endsection
