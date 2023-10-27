<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Invoice</title>

    <style type="text/css">
    @page {
        margin: 10px;
    }

    body {

        -webkit-font-smoothing: antialiased;
        -webkit-text-size-adjust: none;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        height: 100%;
        font-size: 16px !important;
        color: #000000;
        font-family: Arial, sans-serif;
    }

    table {
        border-collapse: collapse !important;
        width: 100%;
    }

    table td {
        border-collapse: collapse;
        vertical-align: top;
    }

    a {
        color: #000000;
        text-decoration: none !important;
    }

    p {
        margin-top: 0;
        margin-bottom: 0.5rem;
        font-size: inherit;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table style="width: 100%;" cellpadding="10">
            <tr>
                <td style="text-align: left; width:230px;">
                    <p><strong>True Leaf retail Inc.</strong></p>
                    <p>U - 15, 6720 Davand Drive Mississauga ON L5T 2K7</p>
                    <p>+1 4377555253</p>
                    <p>
                        <a href="mailto:trueleafretailinc@gmail.com">trueleafretailinc@gmail.com</a>
                    </p>
                    <p>GST/HST Registration No.: <br />727197303RT0001</p>
                </td>
                <td style="
              text-align: left;
              font-size: 24px;
              font-weight: bold;
              color: #2a3e10;
            ">
                    <p>Order {{$order->id}}</p>
                </td>
                <td style="text-align: right; width: 150px">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents(base_path('public/images/logo.jpg'))); ?>"
                        alt="True Leaf" height="150" width="150" />
                </td>
            </tr>
        </table>
        <hr />
        <table style="width: 100%; " cellpadding="10">
            <tr>
                <td style="text-align: left;  width:230px;">
                    <p style="font-size: 14px"><strong>BILL TO</strong></p>
                    <p>{{$order->customer->name}}</p>
                    <p>{{$order->customer->address}}</p>
                </td>
                <td style="text-align: left;  width:230px;">
                    <p style="font-size: 14px"><strong>SHIP TO</strong></p>
                    <p>{{$order->customer->name}}</p>
                    <p>{{$order->customer->address}}</p>
                </td>
                <td style="text-align: center">
                    <table style="width: 100%;">
                        <tr>
                            <td style="
                    padding: 20px 10px;
                    vertical-align: middle;
                    text-align: center;
                    border-bottom: 1px solid #000000;
                    background-color: #d4d9cf;
                    color: #2a3e10;
                  ">
                                <p>DATE<br />{{$order->created_at->format('j F, Y')}}</p>
                            </td>
                            <td style="
                    padding: 20px 10px;
                    vertical-align: middle;
                    text-align: center;
                    border-bottom: 1px solid #000000;
                    background-color: #2a3e10;
                    color: #ffffff;
                  ">
                                <p>PLEASE PAY<br /><strong>${{number_format($order->order_total, 2)}}</strong></p>
                            </td>
                            <td style="
                    padding: 20px 10px;
                    vertical-align: middle;
                    text-align: center;
                    border-bottom: 1px solid #000000;
                    background-color: #d4d9cf;
                    color: #2a3e10;
                  ">
                                <p>DUE DATE<br />{{$order->due_date->format('j F, Y')}}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="width: 100%; border-bottom: 1px solid #000000;" cellpadding="10">
            <tbody>
                <tr
                    style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; text-transform: uppercase; font-size: 14px;">
                    <td style="width: 20%;">
                        <p style="margin-bottom: 0;"><strong>PRODUCT</strong></p>
                    </td>
                    <td style="width: 10%;">
                        <p style="margin-bottom: 0;"><strong>DESCRIPTION</strong></p>
                    </td>
                    <td style="text-align: right; width: 10%;">
                        <p style="margin-bottom: 0;"><strong>TAX</strong></p>
                    </td>
                    <td style="text-align: right; width: 10%;">
                        <p style="margin-bottom: 0;"><strong>QTY</strong></p>
                    </td>
                    <td style="text-align: right; width: 10%;">
                        <p style="margin-bottom: 0;"><strong>RATE</strong></p>
                    </td>
                    <td style="text-align: right; width: 10%;">
                        <p style="margin-bottom: 0;"><strong>AMOUNT</strong></p>
                    </td>
                </tr>
				@foreach($order->order_item as $item)
					<tr>
						<td>
							<p><strong>{{ $item->name }}</strong></p>
						</td>
						<td>
							<p></p>
						</td>
						<td style="text-align: right;">
							<p>{{ $item->title }}</p>
						</td>
						<td style="text-align: right;">
							<p>{{ $item->quantity }}</p>
						</td>
						<td style="text-align: right;">
							<p>{{ $item->sale_price }}</p>
						</td>
						<td style="text-align: right;">
							@php
								$qty = $item->quantity;
								if($item->is_box){
									$qty = $item->quantity * $item->box_size;
								}
								$amount = $qty * $item->sale_price;
								
								$total = $amount + (($amount * $item->tax)/100);
							@endphp
							<p>{{ $total }}</p>
						</td>
					</tr>
				@endforeach
            </tbody>
        </table>
        <table style="width: 100%" cellpadding="10">
            <tr>
                <td width="50%" style="">
                    <p>Thank you for the business!</p>
                </td>
                <td width="50%" style="">
                    <table style="width: 100%">
                        <tr>
                            <td>SUBTOTAL</td>
                            <td style="text-align: right">${{number_format($order->order_total_without_tax, 2)}}</td>
                        </tr>
                        <tr>
                            <td>{{ $item->title }} @ {{$order->tax}}%</td>
                            <td style="text-align: right">${{number_format($order->order_tax, 2)}}</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 16px">TOTAL</td>
                            <td style="text-align: right; padding-bottom: 16px">
                                ${{number_format($order->order_total, 2)}}
                            </td>
                        </tr>
                        <tr style="
                  border-top: 1px solid #000000;
                  border-bottom: 1px solid #000000;
                  text-transform: uppercase;
                ">
                            <td>TOTAL DUE</td>
                            <td style="
                    text-align: right;
                    font-size: 24px;
                    padding-bottom: 10px;
                  ">
                                ${{number_format($order->order_total, 2)}}
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td style="text-align: right; padding-top: 10px">THANK YOU.</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>