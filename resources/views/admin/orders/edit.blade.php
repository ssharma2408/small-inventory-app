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
            {{ trans('global.edit') }} {{ trans('cruds.order.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" id="orderfrm" action="{{ route('admin.orders.update', [$order->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="required"
                                for="sales_manager_id">{{ trans('cruds.order.fields.sales_manager') }}</label>
                            <select class="form-control select2 {{ $errors->has('sales_manager') ? 'is-invalid' : '' }}"
                                name="sales_manager_id" id="sales_manager_id" required disabled>
                                @foreach ($sales_managers as $id => $entry)
                                    <option value="{{ $id }}"
                                        {{ (old('sales_manager_id') ? old('sales_manager_id') : $order->sales_manager->id ?? '') == $id ? 'selected' : '' }}>
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('sales_manager'))
                                <span class="text-danger">{{ $errors->first('sales_manager') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.sales_manager_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="required" for="customer_id">{{ trans('cruds.order.fields.customer') }}</label>
                            <select class="form-control select2 {{ $errors->has('customer') ? 'is-invalid' : '' }}"
                                name="customer_id" id="customer_id" required disabled>
                                @foreach ($customers as $id => $entry)
                                    <option value="{{ $id }}"
                                        {{ (old('customer_id') ? old('customer_id') : $order->customer->id ?? '') == $id ? 'selected' : '' }}>
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('customer'))
                                <span class="text-danger">{{ $errors->first('customer') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.customer_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        @if ($credit_balance > 0)
                            <div class="form-group" id="credit_balance_container">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label
                                            for="credit_balance">{{ trans('cruds.order.fields.credit_balance') }}</label>
                                        <input class="form-control" type="number" name="credit_balance" id="credit_balance"
                                            value="{{ old('credit_balance', $credit_balance) }}" step="0.01" disabled />
                                    </div>
                                    <div class="col-lg-6 pt-4">
                                        <label for="credit_balance"></label>
                                        <input class="form-check-input ml-2 " type="checkbox" name="use_credit"
                                            id="use_credit" checked disabled />
                                        <label class="form-check-label ml-4">Use Credit Balance</label>
                                        <input type="hidden" name="credit_balance_value" id="credit_balance_value"
                                            value="{{ $credit_balance }}" />
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="required" for="order_items">Order Items</label>
                    <div class="order-container mb-2">
                        <div class="order-content">
                            <div class="row mb-1">
                                <div class="col-md-3">
                                    <b>Category/Sub Category/Product</b>
                                </div>
                                
                                <div class="col-md-1">
                                    <b>In Stock</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Min Selling Price</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Max Selling Price</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Box or unit</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Quantity</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Sales Price</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Tax</b>
                                </div>
                                <div class="col-md-1">
                                    <b>Amount</b>
                                </div>
                                <div class="col-md-1">

                                </div>
                            </div>
                            <?php
                            if (!empty($order_items)) {
                                echo '<div class="item_container">';
                                $cnt = 0;
                                foreach ($order_items as $order_item) {
                                    echo '<div class="row mb-1 item_row '.(($cnt!==0)?"border-top mt-3 pt-3 ":"").'">
                            
                                                                                                                                                                									<div class="col-md-3"><div class="form-group"><select class="form-control select2" name="item_category[]" required><option value="' .
                                        $order_item->category_id .
                                        '">' .
                                        $order_item->category_name .
                                        '</option></select></div>
                            
                                                                                                                                                                									<div class="form-group"><select class="subcat form-control select2" name="item_subcategory[]"><option value="' .
                                        $order_item->sub_category_id .
                                        '">' .
                                        $order_item->sub_category_name .
                                        '</option></select>
                                                                                                                                                                									</div>
                            
                                                                                                                                                                									<div class="form-group  "><select class="form-control select2" name="item_name[]" required><option value="' .
                                        $order_item->product_id .
                                        '">' .
                                        $order_item->name .
                                        '</option></select></div></div>
                                                                                                                                                                									<div class="col-md-1">
                                                                                                                                                                										<input class="form-control in_stock" type="number" name="item_stock[]" disabled value="' .
                                        $order_item->stock .
                                        '" />
                                                                                                                                                                									</div>
                                                                                                                                                                									<div class="col-md-1">
                                                                                                                                                                										<input class="form-control min" type="text" name="item_price[]" disabled value="' .
                                        $order_item->selling_price .
                                        '" />
                                                                                                                                                                									</div>
                                                                                                                                                                									<div class="col-md-1">
                                                                                                                                                                										<input class="form-control max" type="text" name="item_max_price[]" disabled value="' .
                                        $order_item->maximum_selling_price .
                                        '" />
                                                                                                                                                                									</div>
                                                                                                                                                                									<div class="col-md-1">';
                                    $checked = '';
                                    if ($order_item->is_box) {
                                        $checked = 'checked';
                                    }
                                    echo '<input class="form-check-input cb ml-0" type="checkbox" name="is_box[]" ' .
                                        $checked .
                                        ' />
                                                                                                                                                                										<label class="form-check-label ml-3">Is Box</label>
                                                                                                                                                                										<div style="font-size:12px" id="box_size">Box Size: ' .
                                        $order_item->box_size .
                                        '</div>
                                                                                                                                                                										<input type="hidden" id="package_val" value="' .
                                        $order_item->box_size .
                                        '" name="package_val" />
                                                                                                                                                                									</div>
                                                                                                                                                                									<div class="col-md-1">
                                                                                                                                                                										<input class="form-control quantity" type="number" name="item_quantity[]" min="1" value="' .
                                        $order_item->quantity .
                                        '"/>
                                                                                                                                                                										<span class="text-danger qty_err"></span>
                                                                                                                                                                									</div>
                                                                                                                                                                									<div class="col-md-1">
                                                                                                                                                                										<input class="form-control sale_price" type="text" name="item_sale_priec[]" value="' .
                                        $order_item->sale_price .
                                        '" required />
                                                                                                                                                                										<span class="text-danger sale_price_err"></span>
                                                                                                                                                                									</div>
                                                                                                                                                                									<div class="col-md-1">
                                                                                                                                                                										<select class="form-control select2 tax_id" name="item_tax_id[]" required>
                                                                                                                                                                										<option value="" >Please Select</option>';
                                    foreach ($taxes as $tax) {
                                        $selected = '';
                                        if ($order_item->tax_id == $tax['id']) {
                                            $selected = 'selected';
                                        }
                                        echo '<option value="' . $tax['id'] . '" ' . $selected . '>' . $tax['title'] . '</option>';
                                    }
                                    echo '</select>
                                                                                                                                                                										<input type="hidden" class="tax_val" value="' .
                                        $order_item->tax .
                                        '" />
                                                                                                                                                                									</div>
                                                                                                                                                                									<div class="col-md-1">';
                                    if ($order_item->is_box) {
                                        $order_item->quantity = $order_item->quantity * $order_item->box_size;
                                    }
                                    $item_value = $order_item->sale_price * $order_item->quantity;
                            
                                    $item_with_tax = $item_value + ($item_value * $order_item->tax) / 100;
                                    echo '<input class="form-control amount" type="text" name="item_amount[]" disabled value="' .
                                        $item_with_tax .
                                        '" />
                                                                                                                                                                									</div>
                                                                                                                                                                									<div class="col-md-1">';
                                    if (!$cnt) {
                                        echo '<span class="add_row" id="add_row" data-key ="">+</span>';
                                    } else {
                                        echo '<span class="remove_row" id="remove_row">-</span>';
                                    }
                            
                                    echo '</div>
                                                                                                                                                                								</div>';
                                    $cnt++;
                                }
                            
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label class="required"
                                for="order_total_without_tax">{{ trans('cruds.order.fields.order_total_without_tax') }}</label>
                            <input class="form-control {{ $errors->has('order_total_without_tax') ? 'is-invalid' : '' }}"
                                type="number" name="order_total_without_tax" id="order_total_without_tax"
                                value="{{ old('order_total_without_tax', $order->order_total_without_tax) }}" step="0.01"
                                required readonly>
                            @if ($errors->has('order_total_without_tax'))
                                <span class="text-danger">{{ $errors->first('order_total_without_tax') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.order_total_without_tax_helper') }}</span>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label class="required" for="order_tax">{{ trans('cruds.order.fields.order_tax') }}</label>
                            <input class="form-control {{ $errors->has('order_tax') ? 'is-invalid' : '' }}" type="number"
                                name="order_tax" id="order_tax" value="{{ old('order_tax', $order->order_tax) }}" step="0.01"
                                required readonly>
                            @if ($errors->has('order_tax'))
                                <span class="text-danger">{{ $errors->first('order_tax') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.order_tax_helper') }}</span>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label class="required">{{ trans('cruds.order.fields.discount_type') }}</label>
                            <div class="row">
                                @foreach (App\Models\Order::DISCOUNT_TYPE_RADIO as $key => $label)
                                    <div class="form-check col-lg-4 ml-2 {{ $errors->has('discount_type') ? 'is-invalid' : '' }}">
                                        <input class="form-check-input" type="radio" id="discount_type_{{ $key }}"
                                            name="discount_type" value="{{ $key }}"
                                            {{ old('discount_type', $order->discount_type) === (string) $key ? 'checked' : '' }}
                                            required>
                                        <label class="form-check-label"
                                            for="discount_type_{{ $key }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @if ($errors->has('discount_type'))
                                <span class="text-danger">{{ $errors->first('discount_type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.discount_type_helper') }}</span>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label for="extra_discount">{{ trans('cruds.order.fields.extra_discount') }}</label>
                            <input class="form-control {{ $errors->has('extra_discount') ? 'is-invalid' : '' }}" type="number"
                                name="extra_discount" id="extra_discount"
                                value="{{ old('extra_discount', $order->extra_discount) }}" step="0.01">
                            @if ($errors->has('extra_discount'))
                                <span class="text-danger">{{ $errors->first('extra_discount') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.extra_discount_helper') }}</span>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label class="required" for="order_total">{{ trans('cruds.order.fields.order_total') }}</label>
                            <input class="form-control {{ $errors->has('order_total') ? 'is-invalid' : '' }}" type="number"
                                name="order_total" id="order_total" value="{{ old('order_total', $order->order_total) }}"
                                step="0.01" required readonly>
                            @if ($errors->has('order_total'))
                                <span class="text-danger">{{ $errors->first('order_total') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.order_total_helper') }}</span>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="comments">{{ trans('cruds.order.fields.comments') }}</label>
                            <textarea class="form-control {{ $errors->has('comments') ? 'is-invalid' : '' }}" name="comments" id="comments">{{ old('comments', $order->comments) }}</textarea>
                            @if ($errors->has('comments'))
                                <span class="text-danger">{{ $errors->first('comments') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.comments_helper') }}</span>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="delivery_note">{{ trans('cruds.order.fields.delivery_note') }}</label>
                            <textarea class="form-control {{ $errors->has('delivery_note') ? 'is-invalid' : '' }}" name="delivery_note"
                                id="delivery_note">{{ old('delivery_note', $order->delivery_note) }}</textarea>
                            @if ($errors->has('delivery_note'))
                                <span class="text-danger">{{ $errors->first('delivery_note') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.delivery_note_helper') }}</span>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="customer_sign">{{ trans('cruds.order.fields.customer_sign') }}</label>
                            <textarea class="form-control {{ $errors->has('customer_sign') ? 'is-invalid' : '' }}" name="customer_sign"
                                id="customer_sign">{{ old('customer_sign', $order->customer_sign) }}</textarea>
                            @if ($errors->has('customer_sign'))
                                <span class="text-danger">{{ $errors->first('customer_sign') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.customer_sign_helper') }}</span>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label class="required">{{ trans('cruds.order.fields.status') }}</label>
                            <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status"
                                id="status" required>
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>
                                    {{ trans('global.pleaseSelect') }}</option>
                                @if (\Auth::user()->roles()->first()->title == 'Sales Manager')
                                    <option value="3" {{ old('status', '3') === (string) 3 ? 'selected' : '' }}>Review
                                    </option>
                                @else
                                    @foreach (App\Models\Order::STATUS_SELECT as $key => $label)
                                        <    value="{{ $key }}"
                                            {{ old('status', $order->status) === (string) $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.status_helper') }}</span>
                        </div>

                        <div class="form-group delivery_agent col-lg-3 col-md-3 col-sm-12">
                            <label class="required"
                                for="delivery_agent_id">{{ trans('cruds.order.fields.delivery_agent') }}</label>
                            <select class="form-control select2 {{ $errors->has('delivery_agent') ? 'is-invalid' : '' }}"
                                name="delivery_agent_id" id="delivery_agent_id">
                                @foreach ($delivery_agents as $id => $entry)
                                    <option value="{{ $id }}"
                                        {{ (old('delivery_agent_id') ? old('delivery_agent_id') : $order->delivery_agent_id ?? '') == $id ? 'selected' : '' }}>
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('delivery_agent'))
                                <span class="text-danger">{{ $errors->first('delivery_agent') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.delivery_agent_helper') }}</span>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-12">
                            <label class="required" for="order_date">{{ trans('cruds.order.fields.order_date') }}</label>
                            <input class="form-control datetime {{ $errors->has('order_date') ? 'is-invalid' : '' }}"
                                type="text" name="order_date" id="order_date"
                                value="{{ old('order_date', $order->order_date) }}" required readonly>
                            @if ($errors->has('order_date'))
                                <span class="text-danger">{{ $errors->first('order_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.order.fields.order_date_helper') }}</span>
                        </div>
                        <div class="form-group col-lg-12">
                            <button class="btn btn-danger mr-2" type="submit">
                                {{ trans('global.save') }}
                            </button>
                            <a href="{{url()->previous()}}" class="btn btn-default ">{{ trans('global.cancel') }}</a>
                        </div>
                    </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function() {
            <?php
            if ($order->status == 4) {
                echo '$(".delivery_agent").show();';
            } else {
                echo '$(".delivery_agent").hide();$(".delivery_agent").val("");';
            }
            ?>
            $(".text-danger").html("");
        });

        $(".add_row").click(function() {
            $(this).parent().parent().parent().append(row_html());
        });
        $(".item_container").on('click', '.remove_row', function() {
            $(this).parent().parent().remove();
            calculate_total();
        });

        function row_html() {
            return '<div class="row mb-3 item_row mt-3 pt-3 border-top"><div class="cat_container col-md-3"><div class="form-group"><?php echo $ddl_html; ?></div> <div class="form-group"><select class="subcat form-control select2" name="item_subcategory[]"><option value="">Please select</option></select></div><div class="form-group"><select class="order_item form-control select2" name="item_name[]" required><option value="">Please select</option></select></div></div><div class="col-md-1"><input class="form-control in_stock" type="number" name="item_stock[]" disabled /></div><div class="col-md-1"><input class="form-control min" type="text" name="item_price[]" disabled /></div><div class="col-md-1"><input class="form-control max" type="text" name="item_max_price[]" disabled /></div><div class="col-md-1"><input class="form-check-input cb ml-0" type="checkbox" name="is_box[]" checked /><label class="form-check-label ml-3">Is Box</label><div style="font-size:12px" id="box_size"></div><div style="font-size:12px" id="box_size"></div><input type="hidden" id="package_val" value="" name="package_val" /></div><div class="col-md-1"><input class="form-control quantity" type="number" name="item_quantity[]" min="1" required /><span class="text-danger qty_err"></span></div><div class="col-md-1"><input class="form-control sale_price" type="text" name="item_sale_priec[]"  required /><span class="text-danger sale_price_err"></span></div><div class="col-md-1"><?php echo $tax_ddl_html; ?><input type="hidden" class="tax_val" value="" /></div><div class="col-md-1"><input class="form-control amount" type="text" name="item_amount[]" disabled /></div><div class="col-md-1"><span class="remove_row" id="remove_row">-</span></div></div>';
        }

        $(document).on("change", ".order_item", function() {
            if ($(this).val() != "") {
                var stock = $(this).parents('.item_row').find('.in_stock');
                var min_selling_price = stock.parent().next().find('input');
                var max_selling_price = min_selling_price.parent().next().find('input');
                var package_val = max_selling_price.parent().next().find('input[type=hidden]');
                var box_size = package_val.prev();
                var taxt_ddl = package_val.parent().next().next().next().find('select');
                var tax_field = package_val.parent().next().next().next().find('input[type=hidden]');

                $.ajax({
                    url: '/admin/orders/get_product_detail/' + $(this).val(),
                    type: 'GET',
                    success: function(data) {
                        if (data.success) {
                            stock.val(data.product.stock);
                            min_selling_price.val(data.product.selling_price);
                            max_selling_price.val(data.product.maximum_selling_price);
                            package_val.val(data.product.box_size);
                            box_size.html('Box Size: ' + data.product.box_size);
                            taxt_ddl.val(data.product.tax_id).change();
                        }
                    }
                });
            }

        });

        $(document).on("keyup", ".quantity, .sale_price", function() {

            var qty = $(this).parent().parent().find(".quantity").val();
            var sale_price = $(this).parent().parent().find(".sale_price").val();
            var min_sale_price = $(this).parent().parent().find(".min").val();
            var in_stock = $(this).parent().parent().find(".in_stock").val();
            var quantity = 0;

            if ((parseFloat(sale_price) < parseFloat(min_sale_price))) {
                $(this).parent().parent().find(".sale_price_err").html(
                    "Sales Price can't be less than Min Selling Price");
            } else {
                $(this).parent().parent().find(".sale_price_err").html("");
            }


            if ($(this).parent().parent().find(".cb").is(':checked')) {
                quantity = qty * $(this).parent().parent().find("#package_val").val();
            } else {
                quantity = qty;
            }

            if ((parseFloat(quantity) > parseFloat(in_stock))) {
                $(this).parent().parent().find(".qty_err").html("Quantity can't be greater than In Stock");
            } else {
                $(this).parent().parent().find(".qty_err").html("");
            }

            if ($(this).parent().parent().find(".cb").is(':checked')) {
                qty = qty * $(this).parent().parent().find("#package_val").val();
            }
            var tax = $(this).parent().parent().find(".tax_val").val();

            if (qty != "" && sale_price != "") {
                $(this).parent().parent().find(".amount").val(qty * sale_price);
            }

            if (tax != "") {
                var amount = qty * sale_price;

                amount = amount + ((amount * tax) / 100);

                $(this).parent().parent().find(".amount").val(amount);
            }
            calculate_total();

        });
        $(document).on("change", "#discount_type_0, #discount_type_1", function() {
            calculate_total();
        });
        $(document).on("keyup", "#extra_discount", function() {
            calculate_total();
        });

        $(document).on("change", ".cb", function() {

            var qty = $(this).parent().parent().find(".quantity").val();
            var sale_price = $(this).parent().parent().find(".sale_price").val();
            var min_sale_price = $(this).parent().parent().find(".min").val();

            var in_stock = $(this).parent().parent().find(".in_stock").val();
            var quantity = 0;

            if ((parseFloat(sale_price) < parseFloat(min_sale_price))) {
                $(this).parent().parent().find(".sale_price_err").html(
                    "Sales Price can't be less than Min Selling Price");
            } else {
                $(this).parent().parent().find(".sale_price_err").html("");
            }


            if ($(this).parent().parent().find(".cb").is(':checked')) {
                quantity = qty * $(this).parent().parent().find("#package_val").val();
            } else {
                quantity = qty;
            }

            if ((parseFloat(quantity) > parseFloat(in_stock))) {
                $(this).parent().parent().find(".qty_err").html("Quantity can't be greater than In Stock");
            } else {
                $(this).parent().parent().find(".qty_err").html("");
            }

            if ($(this).parent().parent().find(".cb").is(':checked')) {
                qty = qty * $(this).parent().parent().find("#package_val").val();
            }

            var tax = $(this).parent().parent().find(".tax_val").val();

            if (qty != "" && sale_price != "") {
                $(this).parent().parent().find(".amount").val(qty * sale_price);
            }

            if (tax != "") {
                var amount = qty * sale_price;

                amount = amount + ((amount * tax) / 100);

                $(this).parent().parent().find(".amount").val(amount);
            }
            calculate_total();
        });

        function calculate_total() {
            var order_total_without_tax = 0,
                extra_discount;
            var order_total = 0;

            extra_discount = $("#extra_discount").val();

            $(".item_row").each(function() {
                var checkb = $(this).find("input[type=checkbox]");
                var package_val = $(this).find("#package_val").val();
                var qty = $(this).find(".quantity").val();
                var sale_price = $(this).find(".sale_price").val();
                var amount = $(this).find(".amount").val();
                if (checkb.is(':checked')) {
                    qty = qty * package_val;
                }
                order_total_without_tax += (qty * sale_price);
                order_total += parseFloat(amount);
            });

            $("#order_total_without_tax").val(order_total_without_tax);
            $("#order_tax").val(order_total - order_total_without_tax);

            if (extra_discount > 0) {
                if ($("#discount_type_0").is(":checked")) {
                    if (extra_discount < order_total) {
                        order_total = order_total - extra_discount;
                    }
                } else {
                    if (((order_total * extra_discount) / 100) < order_total) {
                        order_total = order_total - (order_total * extra_discount) / 100;
                    }
                }
            }

            if ($('#credit_balance_container').is(':visible')) {
                var credit_balance_value = $("#credit_balance_value").val();
                order_total = order_total - credit_balance_value;
            }

            $("#order_total").val(order_total);
        }

        $("#status").change(function() {
            if ($(this).val() == 4) {
                $("#delivery_agent_id").prop('required', true);
                $(".delivery_agent").show();
            } else {
                $("#delivery_agent_id").prop('required', false);
                $(".delivery_agent").hide();
                $(".delivery_agent").val("");
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
                url: '/admin/inventories/get_products/' + cat_id + '/' + sub_cat_id,
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

        $(document).on("change", ".tax_id", function() {

            var tax_id;
            tax_id = $(this).val();
            var qty = $(this).parents('.item_row').find(".quantity").val();
            var sale_price = $(this).parents('.item_row').find(".sale_price").val();

            var min_sale_price = $(this).parents('.item_row').find(".min").val();
            var in_stock = $(this).parents('.item_row').find(".in_stock").val();
            var quantity = 0;

            if ((parseFloat(sale_price) < parseFloat(min_sale_price))) {
                $(this).parents('.item_row').find(".sale_price_err").html(
                    "Sales Price can't be less than Min Selling Price");
            } else {
                $(this).parents('.item_row').find(".sale_price_err").html("");
            }


            if ( $(this).parents('.item_row').find(".cb").is(':checked')) {
                quantity = qty *  $(this).parents('.item_row').find("#package_val").val();
            } else {
                quantity = qty;
            }

            if ((parseFloat(quantity) > parseFloat(in_stock))) {
                $(this).parents('.item_row').find(".qty_err").html("Quantity can't be greater than In Stock");
            } else {
                $(this).parents('.item_row').find(".qty_err").html("");
            }

            //if(tax_id != "" && qty != "" && sale_price != ""){
            if (tax_id != "") {

                if ( $(this).parents('.item_row').find(".cb").is(':checked')) {
                    qty = qty *  $(this).parents('.item_row').find("#package_val").val();
                }

                var tax = $(this).parent().find(".tax_val");
                var amount = $(this).parent().parent().find(".amount");

                $.ajax({
                    url: '/admin/taxes/get_tax/' + tax_id,
                    type: 'GET',
                    success: function(data) {
                        if (data.success) {
                            tax.val(data.tax.tax);
                            var item_total = qty * sale_price;
                            amount.val(item_total + ((item_total * data.tax.tax) / 100))
                            calculate_total();
                        }
                    }
                });
            }
        });

        $("#orderfrm").on("submit", function(event) {

            var is_arror = false;
            $('span.text-danger').each(function() {
                if (!($(this).is(':empty'))) {
                    is_arror = true;
                    return false;
                }
            });
            if (is_arror) {
                event.preventDefault();
            }
        });
    </script>
@endsection
