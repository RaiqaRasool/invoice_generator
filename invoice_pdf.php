<?php
$logo = base64_encode(file_get_contents("./assets/imgs/logo.png"));
$mobile = base64_encode(file_get_contents("./assets/imgs/mobile.png"));
$email = base64_encode(file_get_contents("./assets/imgs/email.png"));
$address_glob = base64_encode(file_get_contents("./assets/imgs/address_glob.png"));
$heart = base64_encode(file_get_contents("./assets/imgs/heart.png"));
$sign = base64_encode(file_get_contents("./assets/imgs/sign.jpg"));
$footer = base64_encode(file_get_contents("./assets/imgs/footer.png"));


require_once("./app/Invoice.php");
$invoice_id = $_GET["invoice_id"];
$invoice = new Invoice();
$invoice_data = $invoice->get_invoice($invoice_id);

$invoice_date = strtotime($invoice_data["invoice_date"]);
$due_date = strtotime($invoice_data["invoice_due_date"]);
$total_bill = 0;
function items_data()
{
    global $invoice;
    global $invoice_id;
    global $total_bill;
    $invoice_items_data = $invoice->get_invoice_item($invoice_id);
    $string = "";
    foreach ($invoice_items_data as $item) {
        $string .=
            '<tr>
        <td class="desc">
        ' . $item[2] . '
        </td>
        <td class="hr">
        ' . $item[3] . '
        </td>
        <td class="hr_rate">
        ' . $item[4] . '
        </td>
        <td class="amount">
        $' . $item[5] . '
        </td>
    </tr>
    ';
        $total_bill += intval($item[5]);
    }
    return $string;
}

require_once("./dompdf/vendor/autoload.php");
// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;
// instantiate and use the dompdf class
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new DOMPDF($options);
$dompdf->loadHtml('<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Godesign</title>
    <style>   
    body{
        
        font-weight:400;
        line-height:100%;

    }
    *{
        padding:0px;
        margin:0px;
        box-sizing:border-box;
    }
    .header .logo img{
        width:60%;
    }
    .header{
        vertical-align:top;
    }
    .header .logo{
        width:6.7818cm;
        height:1.9941645833cm;
        float:left;
    }
    .header .logo img{
        object-fit:contain; 
    }
    .header .contacts-list{
        text-align:right;  
        float:right;
        display:flex;
        line-height:65%;
    }
    .header .contacts-list .contact{
        text-align:center;
        vertical-align:middle;
    }
    .header .contacts-list .contact .text{
        font-size:11px;
    }
    .header .contacts-list .contact .icon img{
        width:50%;
        margin-top:1px;
    }
    .pdf-container{
        padding:50px;
        margin:0cm;
        position:relative;
    }
    @page{
        padding:0cm;
        margin:0cm;
    }
    .hero-section{
        margin-top:20px;
        font-size:12px;
    }
    .hero-section .right table{
        text-align:right;
        float:right;
        margin-top:5px;
    }
    .hero-section .left{
        padding:0px;
    }
    .hero-section .left .invoice_date{
        margin-bottom:0.4519083333cm;
        font-weight:900;
    }
    .hero-section .left .godesign_address{
        line-height:140%;
    }
    .hero-section .right .heading{
        color:#233269;
        font-weight:900;
        font-size:18.667px;
        padding-bottom:8px;
        border-bottom: 5.09px solid #233269;
        width: 187.81px;
    }
    .hero-section .right table tr .invoice_no{
        margin-left:44.7%;
        font-weight:700;
        padding-top:10px;
        width: 187.81px;
        height:12px;
    }
    .client_details{
        margin-top:20px;
        font-size:12px;
    }
    .client_details tr td{
    padding:0px;
    }
    .client_details .heading td{
        font-weight: 900;
        padding-bottom:10px;
    }
    .client_details .client_company td{
        padding-bottom:10px;
    }
    .invoice_details{
        margin-top:40px;
        font-size:11px;
    }
    .invoice_details thead{
        border-top:2px solid black;
        border-bottom:2px solid black;
        text-transform:uppercase;
        font-weight:900;
    }
    .invoice_details thead tr th{
        text-align:center;
        padding:11px 0px;
        font-size:12px;
    }
    .invoice_details thead tr .desc{
        width: 400px;
        text-align:left;
    }
      .invoice_details tbody tr .desc{
        text-align:left;
        width: 400px;
    }
    .invoice_details tbody tr td{
        padding:8px 0px 0px 0px;
        vertical-align:center;
        text-align:center;
    }
    .invoice_details tbody tr .amount{
        text-align:right;
        padding-right:10px;
    }
    .invoice_details thead tr .amount_head{
        text-align:right;
        padding-right:10px;
    }
    .invoice_bottom{
        left:50px;
        right:50px;
        width:700px;
        bottom:410px;
        position:absolute;
    }
    .invoice_bottom_head tr th{
        font-size:12px;
        font-weight:900;
    }
    .invoice_bottom .invoice_bottom_body tr .thanks{
        vertical-align:top;
         padding-left:0px;
    }
    .invoice_bottom .invoice_bottom_head .total_bill{
        text-align:right;
        padding-right:10px;
    }
    .invoice_bottom .invoice_bottom_body tr .due_date{
        font-size:20px;
        width:200px;
        padding-left:20px; 
    }
    .invoice_bottom .invoice_bottom_body tr .total_bill{
        text-align:right;
        padding-right:10px;
        font-size:20px;
        width:100px;
    }
    .invoice_bottom .invoice_bottom_body tr .thanks .sub_table tr .text{
        padding-left:10px;
        line-height:110%;
        font-size:12px;
        width:270px;
    }
    .invoice_bottom .invoice_bottom_body tr .thanks .sub_table tr .heart{
         padding-left:0px;
         vertical-align:middle;
    }
    .invoice_bottom .invoice_bottom_head{
            border-bottom:1px solid black;
    }
    .invoice_bottom .invoice_bottom_head tr th{
        text-align:left;
        padding:5px 0px;
    }
    .invoice_bottom .invoice_bottom_body{
        vertical-align:middle;
    }
    .invoice_bottom .invoice_bottom_body tr .main_table_cell{
       font-weight: 900;
        padding-left:0px;
        padding-top:5px;
    }
    .account_details{
        font-size:11px;
        left:50px;
        right:50px;
        width:700px;
        bottom:290px;
        position:absolute;
    }
    .account_details .account_head{
        border-bottom:1px solid black;
        padding-bottom:2px;
    }
    .account_details .account_head tr th{
        text-align:left;
    }
    .account_details .account_body tr td{
        padding-top:10px;
        line-height:120%;
    }
    .account_details .account_body tr .right_account div{
       float:right;
       padding-right:40px;
    }

    .sign_table{
        float:right;
        position:absolute;
        right:50px;
        bottom:80px;
        width:200px;
    }
    .sign_table .sign_sub{
        width:200px;
    }
    .sign_table .sign_sub tr .text{
        border:none;
        font-size:11px;
        padding-top:5px;
        text-align:left;
        line-height:120%;
    }
    .sign_table .sign_sub tr .sign-image img{
        z-index:-1;
        width:45%;
    }
    .sign_table .sign_sub tr .sign-image{
        border:none;
        border-bottom:1px solid black;
        padding-bottom:5px;
    }

    footer{
        position:fixed;
        bottom:0px;
        left:0px;
        right:0px;
    }
    .registration{
        text-align:center;
       margin-bottom:8px;
       font-size:16px;
    }
    .footer{
        bottom:0px;
        left:0px;
        right:0px;
    }

    </style>
</head>
<body>
<footer>
<table class="registration" width="100%">
    <tr>
    <td>
     SECP Registration No. 0182457
    </td>
    </tr>
</table>
<img width="100%" class="footer" src="data:image;base64,' . $footer . '" alt="footer"/>
</footer>
    <div class="pdf-container">
        <table class="header" width="100%">
            <td class="logo">
                <img src="data:image;base64,' . $logo . '" alt="logo"/>
            </td>
            <td class="contacts-list">
                <table class="contact">
                    <tr>
                        <td class="icon">
                            <img src="data:image;base64,' . $mobile . '" alt="mobile"/>
                        </td>
                        <td class="text">
                            +92(307) 607-7533
                        </td>
                    </tr>
                </table>
                <table class="contact">
                    <tr>
                        <td class="icon">
                            <img src="data:image;base64,' . $email . '" alt="mobile"/>
                        </td>
                        <td class="text">
                            info@godesign.pk
                        </td>
                    </tr>
                </table>
                <table class="contact website">
                    <tr>
                        <td class="icon">
                            <img src="data:image;base64,' . $address_glob . '" alt="mobile"/>
                        </td>
                        <td class="text">
                            www.godesign.pk
                        </td>
                    </tr>
                </table>
            </td>
            </tr>
        </table>   

        <table class="hero-section" width="100%">
        <tr>
            <td class="left">
                <div class="invoice_date">' . date("d F, Y", $invoice_date) . '</div>
                <div class="godesign_address">
                    Street 25, SEC-I-10/4 I-10,<br/>
                    Islamabad Capital Territory <br/>
                    44800 Pakistan
                </div>
            </td>
            <td class="right">
            <table>
            <tr>
                <td class="heading">
                    INVOICE
                </td>
            </tr>
            <tr>
                <td class="invoice_no">
                    Invoice No. GD-' . (($invoice_id % 2000) + 2000) . '
                </td>
            </tr>
            <table>
            </td>
            </tr>
        </table>
        <table class="client_details">
            <tr class="heading">
            <td>Bill To</td>
            </tr>
            <tr class="client_name">
            <td>' . $invoice_data["invoice_receiver_name"] . '</td>
            </tr>
            <tr class="client_company">
            <td>' . $invoice_data["invoice_receiver_company"] . '</td>
            </tr>
            <tr class="client_street">
            <td>' . $invoice_data["invoice_receiver_street"] . '</td>
            </tr>
            <tr class="client_address">
            <td>' . $invoice_data["invoice_receiver_city"] . ', ' . $invoice_data["invoice_receiver_state"] . ', ' . $invoice_data["invoice_receiver_country"] . '</td>
            </tr>
            <tr class="client_zip">
            <td>' . $invoice_data["invoice_receiver_zip"] . '</td>
            </tr>
        </table>
        
        <table class="invoice_details" width="100%">
            <thead>
                <tr>
                    <th class="desc">Description</th>
                    <th class="hrs_head">Hours</th>
                    <th class="hr_rate_head">Hourly Rate</th>
                    <th class="amount_head">Amount</th>
                </tr>
            </thead>
            <tbody>
                ' . items_data() . '
            </tbody>
        </table>
        <table class="invoice_bottom" width="100%">
            <thead class="invoice_bottom_head">
                <tr>
                    <th class="thanks">
                    </th>
                    <th class="due_date">
                    DUE BY
                    </th>
                    <th  class="total_bill">
                    TOTAL DUE
                    </th>
                </tr>
            </thead>
            <tbody class="invoice_bottom_body">
                <tr>
                    <td class="main_table_cell thanks">
                        <table class="sub_table">
                            <tr>
                                <td class="heart">
                                    <img width="35" src="data:image;base64,' . $heart . '" alt="heart"/>
                                </td>
                                <td class="text">
                                    THANK YOU FOR YOUR BUSINESS. PLEASE DEPOSIT PAYMENT BY DUE DATE
                                </td>
                            </tr>   
                        </table>
                    </td>
                    <td class="main_table_cell due_date ">
                        ' . date("d F Y") . '
                    </td>

                    <td class="main_table_cell total_bill">
                        $' . $total_bill . '
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="account_details" width="100%">
            <thead class="account_head">
                <tr>
                    <th>
                        BANK ACCOUNT DETAILS
                    </th>
                    <th>
                    </th>
                </tr>
            </thead>
            <tbody class="account_body">
            <tr>
                <td>
                    ACCOUNT NAME: <b>GODESIGN TECHNOLOGIES LLP</b><br/>
                    BANK NAME: FAYSAL BANK LIMITED<br/>
                    ADDRESS: H#373 ST. 25 SEC-I-10/4 44800,<br/>
                    ISLAMABAD,PAKISTAN
                </td>
                <td class="right_account">
                    <div>
                        SWIFT/SORT/ROUTING CODE: FAYSPKKA<br/>
                        IBAN: PK87FAYS0169007000009395<br/>
                        BRANCH CODE: 0169<br/>
                        ACCOUNT NUMBER: 0169007000009395
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="sign_table" width="100%">
            <table class="sign_sub">
                <tr>
                    <td class="sign-image">
                        <img src="data:image;base64,' . $sign . '" alt="sign"/>
                    </td>
                </tr>
                <tr>
                    <td class="text">
                        Company Official Signature<br/>
                        Hafiz Muhammad Usman<br/>
                        Chief Technology Officer<br/>
                     </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>');

// Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
if ($_GET["mode"] == 'd')
    $dompdf->stream("invoice_godesign.pdf", array("Attachment" => true));
else
    $dompdf->stream("invoice_godesign.pdf", array("Attachment" => false));
