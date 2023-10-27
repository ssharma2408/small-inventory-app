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
                    <p>Invoice 4500</p>
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
                    <p>Mr Asad</p>
                    <p>Apna Farm Halal Meat &amp; Grocery, Brampton</p>
                    <p>50 Sky Harbour Drive Unit 5-7 Brampton L6Y 6B8</p>
                </td>
                <td style="text-align: left;  width:230px;">
                    <p style="font-size: 14px"><strong>SHIP TO</strong></p>
                    <p>Mr Asad</p>
                    <p>Apna Farm Halal Meat &amp; Grocery, Brampton</p>
                    <p>50 Sky Harbour Drive Unit 5-7 Brampton L6Y 6B8</p>
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
                                <p>DATE<br />21/09/2023</p>
                            </td>
                            <td style="
                    padding: 20px 10px;
                    vertical-align: middle;
                    text-align: center;
                    border-bottom: 1px solid #000000;
                    background-color: #2a3e10;
                    color: #ffffff;
                  ">
                                <p>PLEASE PAY<br /><strong>$1,096.80</strong></p>
                            </td>
                            <td style="
                    padding: 20px 10px;
                    vertical-align: middle;
                    text-align: center;
                    border-bottom: 1px solid #000000;
                    background-color: #d4d9cf;
                    color: #2a3e10;
                  ">
                                <p>DUE DATE<br />21/10/2023</p>
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
                <tr>
                    <td>
                        <p><strong>Apple, Barbican - 330ml x (4x6)</strong></p>
                    </td>
                    <td>
                        <p>Premium quality</p>
                    </td>
                    <td style="text-align: right;">
                        <p>HST ON</p>
                    </td>
                    <td style="text-align: right;">
                        <p>3</p>
                    </td>
                    <td style="text-align: right;">
                        <p>24.00</p>
                    </td>
                    <td style="text-align: right;">
                        <p>72.00</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>
                            <strong>Pomegranate, Barbican - 330ml x </strong><strong>(4x6)</strong>
                        </p>
                    </td>
                    <td>
                        <p>Premium Quality</p>
                    </td>
                    <td style="text-align: right;">
                        <p>HST ON</p>
                    </td>
                    <td style="text-align: right;">
                        <p>6</p>
                    </td>
                    <td style="text-align: right;">
                        <p>24.00</p>
                    </td>
                    <td style="text-align: right;">
                        <p>144.00</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Peach, Barbican - 330ml x (4x6)</strong></p>
                    </td>
                    <td>
                        <p>Premium quality</p>
                    </td>
                    <td style="text-align: right;">
                        <p>HST ON</p>
                    </td>
                    <td style="text-align: right;">
                        <p>5</p>
                    </td>
                    <td style="text-align: right;">
                        <p>24.00</p>
                    </td>
                    <td style="text-align: right;">
                        <p>120.00</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>
                            <strong>Pineapple , Barbican - 330ml x </strong><strong>(4x6)</strong>
                        </p>
                    </td>
                    <td>
                        <p>Premium quality</p>
                    </td>
                    <td style="text-align: right;">
                        <p>HST ON</p>
                    </td>
                    <td style="text-align: right;">
                        <p>5</p>
                    </td>
                    <td style="text-align: right;">
                        <p>24.00</p>
                    </td>
                    <td style="text-align: right;">
                        <p>120.00</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>
                            <strong>Strawberry, Barbican - 330ml x </strong><strong>(4x6)</strong>
                        </p>
                    </td>
                    <td>
                        <p>Premium quality</p>
                    </td>
                    <td style="text-align: right;">
                        <p>HST ON</p>
                    </td>
                    <td style="text-align: right;">
                        <p>3</p>
                    </td>
                    <td style="text-align: right;">
                        <p>24.00</p>
                    </td>
                    <td style="text-align: right;">
                        <p>72.00</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Raspberry, Barbican - 330ml </strong><strong>(4x6)</strong></p>
                    </td>
                    <td>
                        <p>Premium quality</p>
                    </td>
                    <td style="text-align: right;">
                        <p>HST ON</p>
                    </td>
                    <td style="text-align: right;">
                        <p>2</p>
                    </td>
                    <td style="text-align: right;">
                        <p>24.00</p>
                    </td>
                    <td style="text-align: right;">
                        <p>48.00</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Lemon, Barbican - 330ml (4x6)</strong></p>
                    </td>
                    <td>
                        <p>Premium quality</p>
                    </td>
                    <td style="text-align: right;">
                        <p>HST ON</p>
                    </td>
                    <td style="text-align: right;">
                        <p>1</p>
                    </td>
                    <td style="text-align: right;">
                        <p>24.00</p>
                    </td>
                    <td style="text-align: right;">
                        <p>24.00</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Shangrila Chilli garlic sauce 800g</strong></p>
                    </td>
                    <td>
                        <p>12&nbsp;&nbsp;&nbsp; 800g</p>
                    </td>
                    <td style="text-align: right;">
                        <p>Out of Scope</p>
                    </td>
                    <td style="text-align: right;">
                        <p>1</p>
                    </td>
                    <td style="text-align: right;">
                        <p>48.00</p>
                    </td>
                    <td style="text-align: right;">
                        <p>48.00</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Shangrila Soya sauce 800g</strong></p>
                    </td>
                    <td>
                        <p>12&nbsp;&nbsp;&nbsp; 800g</p>
                    </td>
                    <td style="text-align: right;">
                        <p>Out of Scope</p>
                    </td>
                    <td style="text-align: right;">
                        <p>1</p>
                    </td>
                    <td style="text-align: right;">
                        <p>48.00</p>
                    </td>
                    <td style="text-align: right;">
                        <p>48.00</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Shangrila - Chilli Sauce</strong></p>
                    </td>
                    <td>
                        <p>400ml</p>
                    </td>
                    <td style="text-align: right;">
                        <p>Out of Scope</p>
                    </td>
                    <td style="text-align: right;">
                        <p>1</p>
                    </td>
                    <td style="text-align: right;">
                        <p>48.00</p>
                    </td>
                    <td style="text-align: right;">
                        <p>48.00</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Shangrila Soya sauce 800g</strong></p>
                    </td>
                    <td>
                        <p>400ml</p>
                    </td>
                    <td style="text-align: right;">
                        <p>Out of Scope</p>
                    </td>
                    <td style="text-align: right;">
                        <p>1</p>
                    </td>
                    <td style="text-align: right;">
                        <p>48.00</p>
                    </td>
                    <td style="text-align: right;">
                        <p>48.00</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Green cardamom 100g</strong></p>
                    </td>
                    <td>
                        <p>100g x 2cs</p>
                    </td>
                    <td style="text-align: right;">
                        <p>Out of Scope</p>
                    </td>
                    <td style="text-align: right;">
                        <p>72</p>
                    </td>
                    <td style="text-align: right;">
                        <p>3.15</p>
                    </td>
                    <td style="text-align: right;">
                        <p>226.80</p>
                    </td>
                </tr>

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
                            <td style="text-align: right">1,018.80</td>
                        </tr>
                        <tr>
                            <td>HST (ON) @ 13%</td>
                            <td style="text-align: right">78.00</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 16px">TOTAL</td>
                            <td style="text-align: right; padding-bottom: 16px">
                                1,096.80
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
                                $1,096.80
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