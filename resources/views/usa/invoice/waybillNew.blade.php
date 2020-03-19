<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <title>Blank</title>
        <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
        <style>
                .general {
                        position: relative;
                }

                body {
                        width: 210mm;
                        height: 297mm;
                        top: 0;
                        left: 0;
                        position: absolute;
                        font-size: 12px;

                }

                .barcode p {
                        font-family: 'Libre Barcode 39';
                        font-size: 55px;
                        height: 56px;
                }

                .barcode {
                        position: relative;
                }

                .barcode span {
                        position: absolute;
                        left: 50%;
                        bottom: 0;
                        transform: translateX(-50%);
                }

                .right-form {
                        position: absolute;
                        right: 0;
                        height: 100%;

                }
                /* The container */
                .container {
                        display: block;
                        position: relative;
                        padding-left: 35px;
                        margin-bottom: 12px;
                        cursor: pointer;
                        font-size: 15px;
                        font-weight: bold;
                        -webkit-user-select: none;
                        -moz-user-select: none;
                        -ms-user-select: none;
                        user-select: none;
                }

                .container.small {
                        padding-left: 15px;
                }

                /* Hide the browser's default checkbox */
                .container input {
                        position: absolute;
                        opacity: 0;
                        cursor: pointer;
                        height: 0;
                        width: 0;
                }

                /* Create a custom checkbox */
                .checkmark {
                        position: absolute;
                        top: 0;
                        left: 0;
                        height: 15px;
                        width: 15px;
                        border:1px solid black;
                        background:transparent;
                }

                .container.small .checkmark {
                        position: absolute;
                        top: 2px;
                        left: 0;
                        height: 10px;
                        width: 10px;
                        border:1px solid black;
                        background:transparent;
                }

                /* Create the checkmark/indicator (hidden when not checked) */
                .checkmark:after {
                        content: "";
                        position: absolute;
                        display: none;
                }

                /* Show the checkmark when checked */
                .container input:checked ~ .checkmark:after {
                        display: block;
                }

                /* Style the checkmark/indicator */
                .container .checkmark:after {
                        left: 4px;
                        top: -1px;
                        width: 5px;
                        height: 10px;
                        border: solid black;
                        border-width: 0 3px 3px 0;
                        -webkit-transform: rotate(45deg);
                        -ms-transform: rotate(45deg);
                        transform: rotate(45deg);
                }

                .container.small .checkmark:after {
                        left: 3px;
                        top: -1px;
                        width: 3px;
                        height: 8px;
                        border: solid black;
                        border-width: 0 2px 2px 0;
                        -webkit-transform: rotate(45deg);
                        -ms-transform: rotate(45deg);
                        transform: rotate(45deg);
                }

                .table-one th , tr{
                        font-weight: normal;
                        font-size: 13px;
                }

                td {
                        height: 33px;
                        box-shadow: 1px 1px 2px rgba(0,0,0, .5);
                        text-align: center;
                }

                table {
                        width: 100%;
                }

                ul {
                        list-style: none;
                        border:1px solid black;
                        display: inline-block;
                        padding:0;
                        margin: 0;
                }

                ul li {
                        height: 29px;
                        font-size: 13px;
                        padding: 3px;
                        line-height: 14px;
                }

                ul li:nth-child(2) , ul li:nth-child(3) {
                        border-bottom: 1px dotted;
                }

                .total-value {
                        position: absolute;
                        right: 30px;
                        width: 95%;
                        margin-right: 7px;;
                }


        </style>
        <style>
                @media print {
                        body, html {
                                width: 100vw;
                                height: 100vh;
                                margin:0;
                        }

                        body{
                                width: 95%;
                                height: auto;
                                margin: 50px auto;
                        }

                        body {
                                size: landscape;
                        }

                        .total-value {
                                position: absolute;
                                right: 23px;
                                width: 100%;
                                margin-right: 0;
                        }
                }
        </style>
</head>
<body>
<div class="general">
        <div class="left-form" style="width:49%;display: inline-block;">
                <div class="logo" style="border-bottom: 2px solid black;padding-left: 20px;">
                        <h1>SMR Logistics NY LLC</h1>
                </div>
                <div class="left-inner-first" style="border-bottom: 2px solid black">
                        <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 0">
                                <h6 style="font-weight: bold; font-size: 12px; margin:0 ">1. From (Shipper)</h6>
                                <div style="width: 49%; display: inline-block">
                                         Date
                                        <p style="display: inline-block;;margin: 0 0 0 15px;">{{ date('d') }}/{{ date('m') }}/{{ date('Y') }}</p>
                                </div>
                                <div style="width: 49%; display: inline-block">
                                       Account Number
                                        <p style="margin:0 0 0 15px;display:inline-block;"> </p>
                                </div>
                        </div>
                        <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 0">
                                <div style="width: 49%; display: inline-block;">
                                        Shippers's name
                                        <p style="margin: 0 0 0 15px;display: inline-block"> </p>
                                </div>
                                <div style="width: 49%; display: inline-block; margin-top: 0">
                                        Phone
                                        <p style="margin: 0 0 0 15px; display: inline-block;"><?php
                                            if($data->client_id>0 and $data->client_phone!=null){
                                                echo $data->client_phone;
                                            }elseif($data->person_id>0 and $data->person_phone!=null){
                                                echo $data->person_phone;
                                            }
                                            else{
                                                echo $data->phone;
                                            }
                                            ?></p>
                                </div>
                        </div>
                        <div style="border-bottom: 1px solid black;padding: 5px 0;margin: 0 5px 0">
                                <div style="position: relative">
                                        <p style="display: inline-block; margin: 0;">Company</p>
                                        <p style="position: absolute; left: 50%;top: 50%;margin: 0;transform: translate(-50%,-50%);">SMR Logistics NY LLC</p>
                                </div>
                        </div>
                        <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 0">
                                <div style="position: relative">
                                        <p style="display: inline-block; margin: 0;">Address</p>
                                        <p style="position: absolute; left: 50%;top: 50%;margin: 0;transform: translate(-50%,-50%);">900 Port Reading Avenue Unit#B5</p>
                                </div>
                        </div>
                        <div style="border-bottom: 1px solid black;padding: 6px 0;height: 30px;margin: 0 5px 0">
                        </div>
                        <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 10px">
                                <div style="width:34%; display: inline-block;">
                                        <p style="display: inline-block; margin: 0;">City / State</p>
                                        <p style="display: inline-block; margin:  0 0 0 15px;">Newark</p>
                                </div>
                                <div style="width:32%; display: inline-block">
                                        <p style="display: inline-block; margin: 0;">Zip Code</p>
                                        <p style="display: inline-block; margin:  0 0 0 15px;">19711</p>
                                </div>
                                <div style="width: 32%; display: inline-block;">
                                        <p style="display: inline-block; margin: 0;" >Country</p>
                                        <p style="display: inline-block; margin:  0 0 0 15px;">USA</p>
                                </div>
                        </div>
                </div>
                <div class="left-inner-second" style="border-bottom: 2px solid black">
                        <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 0">
                                <h6 style="font-weight: bold; font-size: 12px; margin:0 0 10px 0 ">2. To (Recipient's)</h6>
                                <div style="width: 57%; display: inline-block;">
                                        <p style="display: inline-block; margin: 0;">Name</p>
                                        <p style="display: inline-block; margin:  0 0 0 15px;"><?php
                                            if($data->client_id>0 and $data->client_name!=null){
                                                echo $data->client_name." ".$data->client_surname."| 1".str_pad($data->client_id, 6, "0", STR_PAD_LEFT);
                                            }elseif($data->person_id>0 and $data->person_name!=null){
                                                echo $data->person_name." ".$data->person_surname."| ".$data->person_id;
                                            }else{
                                                echo $data->name." ".$data->surname ." | ". $data->uniqid;
                                            }
                                            ?></p>
                                </div>
                                <div style="width: 40%; display: inline-block;">
                                        <p style="display: inline-block; margin: 0;">Phone</p>
                                        <p  style="display: inline-block; margin:  0 0 0 15px;"><?php
                                            if($data->client_id>0 and $data->client_phone!=null){
                                                echo $data->client_phone;
                                            }elseif($data->person_id>0 and $data->person_phone!=null){
                                                echo $data->person_phone;
                                            }
                                            else{
                                                echo $data->phone;
                                            }
                                            ?></p>
                                </div>
                        </div>
                        <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 0">
                                <div style="position: relative">
                                        <p style="display: inline-block; margin: 0;">Address</p>
                                        <p style="position: absolute; left: 50%;top: 50%;margin: 0;transform: translate(-50%,-50%);"><?php
                                            if($data->client_id>0 and $data->client_address!=null){
                                                echo $data->client_address;
                                            }elseif($data->person_id>0 and $data->person_address!=null){
                                                echo $data->person_address;
                                            }else{
                                                echo $data->address;
                                            }
                                            ?></p>
                                </div>
                        </div>

                        <div style="border-bottom: 1px solid black;padding: 6px 0;height: 30px;margin: 0 5px 0">
                        </div>
                        <div style="border-bottom: 1px solid black;padding: 6px 0; margin: 0 5px 10px">
                                <div style="width:30%; display: inline-block">
                                        <p style="display: inline-block; margin: 0;">City / State</p>
                                        <p style="display: inline-block; margin:  0 0 5px 15px;">Baku </p>
                                </div>
                                <div style="width:40%; display: inline-block">
                                        <p style="display: inline-block; margin: 0;">Zip Code</p>
                                        <p style="display: inline-block; margin: 0 0 5px 15px">
                                                <span>AZ1000</span><br>
                                                <span>Required</span>
                                        </p>
                                </div>
                                <div style="width: 28%; display: inline-block">
                                        <p style="display: inline-block; margin: 0;">Country</p>
                                        <p style="display: inline-block; margin: 0 0 5px 15px">Azerbaijan</p>
                                </div>
                        </div>
                </div>
                <div class="left-inner-third">
                        <div style="padding-top: 5px;margin: 0 5px 0">
                                <h6 style="font-weight: bold; font-size: 12px; margin:0 ">3.  Shipper's Authorization & Signature</h6>
                                <div style="width: 49%; display: inline-block; margin-top: 0">
                                        <p style="display: inline-block;margin: 0;">Signature</p>
                                        <p style="display: inline-block;border-bottom: 1px solid black;height: 30px;width: 71%;margin: 0;"></p>
                                </div>
                                <div style="width: 49%; display: inline-block; margin-top: 0;position:relative;">
                                        <p style="display:inline-block;margin: 0;">Phone</p>
                                        <p style="display: inline-block;border-bottom: 1px solid black;height: 20px;width: 60%;margin: 0;position: absolute;bottom: 4px;">
                                                <span style="margin-left: 20px">718-995-8101</span>
                                        </p>
                                </div>
                        </div>
                </div>
        </div>
        <div class="right-form" style="width: 49%;display: inline-block;padding-left: 20px">
                <div style="border-bottom: 2px solid black;padding: 0 20px;display: flex;align-items: center;justify-content: space-between;">
                        <div class="">
                                {!! $barcode !!}
                        </div>
                        <h2 style="width: 35%;margin-top: 0;text-align: right">{{ $barcode_code." " }}

                        </h2>


                </div>
                <div class="left-inner-fourth" style="border-bottom: 1px solid black">
                        <div style="padding-top: 5px;margin: 0 5px 0">
                                <h6 style="font-weight: bold; font-size: 12px; margin:0 ">4. International Services</h6>
                                <div style="display: flex;align-items: center">
                                        <div class="check-part" style="border: 1px solid black;width: 34%;padding: 2px;display: flex;flex-direction: column;justify-content: center;align-items: center;">
                                                <label class="container" style="margin: 5px 0">By air
                                                        <input type="checkbox" checked>
                                                        <span class="checkmark"></span>
                                                </label>
                                        </div>
                                        <div class="check-part" style="border: 1px solid black;width: 34%;padding: 2px; margin: 0 10px;display: flex;flex-direction: column;justify-content: center;align-items: center;">
                                                <label class="container" style="margin: 5px 0">By sea
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                </label>
                                        </div>
                                        <div class="check-part" style=" display: flex;width: 24%;padding: 5px; height: 90px;align-items: center;justify-content: center">
                                                <p style="font-size: 15px; text-align: center;margin:0;">
                                                        Shipping price <br>
                                                        <span>{{ $data->shipping_price }}  USD</span> <br>
                                                        <span>Invoice price:  {{$data->price}} USD</span>
                                                </p>
                                        </div>
                                </div>
                                <p style="margin: 0;font-size: 12px;">It has survived not only five centuries, but also the leap into electronic </p>
                                <p style="margin: 0;font-size: 12px;">***It has survived not only five centuries, but also the leap into electronic </p>
                        </div>
                </div>
                <div class="right-inner-fifth" style="border-bottom: 1px solid black;position:relative;">
                        <h6 style="font-weight: bold; font-size: 15px; margin:5px 0 5px 0 ">5.  Transportation Charges Bill to:</h6>
                        <div style="width: 36%;display: inline-block">
                                <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;"> Sender
                                        <input type="checkbox" checked>
                                        <span class="checkmark"></span>
                                </label>
                                <div style="position: relative;margin-bottom: 10px">
                                        <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;display: inline-block"> Third party (acct.)
                                                <input type="checkbox">
                                                <span class="checkmark"></span>
                                        </label>
                                        <p style="display: inline-block;border-bottom: 1px solid black;height: 20px;width: 20%;margin: 0; position:absolute;bottom: 3px;">
                                                <span style="margin-left: 10px"></span>
                                        </p>
                                </div>
                        </div>
                        <div style="width: 18%;display: inline-block">
                                <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;">Recipient
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                </label>
                        </div>
                        <div style="width: 44%;display: inline-block">
                                <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;"> Cash / Credit Card
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                </label>
                                <div style="position: relative;margin-bottom: 10px">
                                        <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;display: inline-block"> C.O.D.S
                                                <input type="checkbox">
                                                <span class="checkmark"></span>
                                        </label>
                                        <p style="display: inline-block;border-bottom: 1px solid black;height: 20px;width: 25%;margin: 0; position:absolute;bottom: 3px;">
                                                <span style="margin-left: 10px"></span>
                                        </p>
                                </div>
                        </div>
                </div>
                <div class="right-inner-sixth">
                        <div style="padding-top: 5px;margin: 0 5px 0">
                                <h6 style="font-weight: bold; font-size: 15px; margin:0 ">6. Shipment Information</h6>
                                <div style="display: flex;position:relative;">
                                        <div class="table-one" style="width: 20%;display: inline-block">
                                                <table>
                                                        <tr>
                                                                <th>Agirlik <br> Weight</th>
                                                        </tr>
                                                        <tr>
                                                                <td>1</td>
                                                                <td>{{ $data->weight }}</td>
                                                        </tr>
                                                        <tr>
                                                                <td></td>
                                                                <td></td>
                                                        </tr>
                                                        <tr>
                                                                <td></td>
                                                                <td></td>
                                                        </tr>
                                                </table>
                                        </div>
                                        <div class="table-two" style="width: 50%;display: inline-block;margin-left: 60px">
                                                <table>
                                                        <caption style="font-weight: bold;margin-top: 6px">DIMENSIONS</caption>
                                                        <tr>
                                                                <td>L</td>
                                                                <td style="box-shadow: none;width: 10px;font-weight: bold;">x</td>
                                                                <td>W</td>
                                                                <td style="box-shadow: none;width: 10px;font-weight: bold;">x</td>
                                                                <td>H</td>
                                                        </tr>
                                                        <tr>
                                                                <td>L</td>
                                                                <td style="box-shadow: none;width: 10px;font-weight: bold;">x</td>
                                                                <td>W</td>
                                                                <td style="box-shadow: none;width: 10px;font-weight: bold;">x</td>
                                                                <td>H</td>
                                                        </tr>
                                                        <tr>
                                                                <td>L</td>
                                                                <td style="box-shadow: none;width: 10px;font-weight: bold;">x</td>
                                                                <td>W</td>
                                                                <td style="box-shadow: none;width: 10px;font-weight: bold;">x</td>
                                                                <td>H</td>
                                                        </tr>
                                                </table>
                                        </div>
                                        <div class="dimen-part" style="display:inline-block;width: 10%;position: absolute;right: 12%;bottom: 0;">
                                                <label class="container small" style="margin: 0 0 0 20px;font-weight: normal;font-size: 13px;">cm
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                </label>
                                                <label class="container small" style="margin: 0 0 0 20px;font-weight: normal;font-size: 13px;">inch
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                </label>
                                                <label class="container small" style="margin: 0 0 0 20px;font-weight: normal;font-size: 13px;">cm
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                </label>
                                                <label class="container small" style="margin: 0 0 0 20px;font-weight: normal;font-size: 13px;">inch
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                </label>
                                                <label class="container small" style="margin: 0 0 0 20px;font-weight: normal;font-size: 13px;">cm
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                </label>
                                                <label class="container small" style="margin: 0 0 0 20px;font-weight: normal;font-size: 13px;">inch
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                </label>
                                                <label class="container small" style="margin: 0 0 0 20px;font-weight: normal;font-size: 13px;">cm
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                </label>
                                        </div>
                                </div>
                                <div style="position:relative; margin-top: 5px">
                                        <div class="table-two" style="width: 20%;display: inline-block;" class="table-two">
                                                <table>
                                                        <caption style="font-size: 13px;"> Total</caption>
                                                        <tr>
                                                                <td>Pcs</td>
                                                                <td>Weight</td>
                                                        </tr>
                                                </table>
                                        </div>
                                        <div class="dimen-part" style="display:inline-block;width: 15%;position: relative;bottom: 7px;">
                                                <label class="container small" style="margin:0;font-weight: normal;font-size: 13px;">Kg
                                                        <input type="checkbox" checked>
                                                        <span class="checkmark"></span>
                                                </label>
                                                <label class="container small" style="margin:0;font-weight: normal;font-size: 13px;">Lbs
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                </label>
                                        </div>
                                        <div style="width: 20%;display: inline-block">
                                                <label class="container small" style="margin: 0 0 0 20px;font-weight: normal;font-size: 13px;position: relative;bottom: 7px;">Envelope
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                </label>
                                        </div>
                                        <div style="width: 20%;display: inline-block">
                                                <label class="container small" style="margin: 0 0 0 20px;font-weight:normal;font-size: 13px;position: relative;bottom: 7px;">Pack
                                                        <input type="checkbox" checked>
                                                        <span class="checkmark"></span>
                                                </label>
                                        </div>
                                        <div style="width: 20%;display: inline-block">
                                                <label class="container small" style="margin: 0 0 0 20px;font-weight: normal;font-size: 13px;position: relative;bottom: 7px;"> Box
                                                        <input type="checkbox">
                                                        <span class="checkmark"></span>
                                                </label>
                                        </div>
                                </div>
                                <div class="table-three" style="position:relative; display: flex">
                                        <ul style="width: 30%;text-align: center;">
                                                <li style="border-bottom: 1px solid">
                                                        Commodity Description
                                                </li>
                                                <li>
                                                        {{ $data->product_type_name }}
                                                </li>
                                        </ul>
                                        <ul style="width: 22%;text-align: center;border-left: none;">
                                                <li style="border-bottom: 1px solid">
                                                        Harmonized Code
                                                </li>
                                                <li>
                                                </li>
                                        </ul>
                                        <ul style="width: 22%;text-align: center;border-left: none;">
                                                <li style="border-bottom: 1px solid">
                                                        Country of Manufacture
                                                </li>
                                                <li>
                                                        USA
                                                </li>
                                        </ul>
                                        <ul style="width: 22%;text-align: center;border-left: none;">
                                                <li style="border-bottom: 1px solid">
                                                        Value  for Customs
                                                </li>
                                                <li>
                                                </li>
                                        </ul>
                                </div>
                                <div class="total-value">
                                        <ul style="border:none;width: 100%">
                                                <li class="total-second" style="height: 30px;float: right; width: 22%; display: inline-block;border-width:0 1px 1px 1px ; border-style: solid;text-align: center ">
                                                        {{ number_format(($data->price) + (float) $data->shipping_price, 2) }} USD
                                                </li>
                                                <li style="width: 22%;display: inline-block; border:none;text-align: center; float: right">

                                                        Total Value <br>
                                                        For Customs
                                                </li>
                                        </ul>
                                </div>
                        </div>

                        <div class="right-inner-ninth" style="display: flex;border-bottom: 1px solid;margin-top:40px;">
                                <div class="left-inner-fifth" style="border-top:1px solid;position:relative;padding: 3px 0 0 3px; width: 38%;display: inline-block;">
                                        <h6 style="font-weight: bold; font-size: 15px; margin:0 ">7. Picked-Up By</h6>
                                        <p style="font-size: 13px;margin-bottom: 0"> Name:
                                                <span>Kayhan Ozcilingir</span>
                                        </p>
                                        <p style="font-size: 13px;display: inline-block; width: 49%; "> Time:
                                                <span>{{date('H:i')}}</span>
                                        </p>
                                        <p style="font-size: 13px;display: inline-block; width: 49%; "> Date:
                                                <span>{{date('d-m-Y')}}</span>
                                        </p>
                                </div>
                                <div class="left-inner-fifth" style="border-width:1px 0 0 1px;border-style: solid;position:relative;padding: 3px 0 0 3px;width: 60%;display: inline-block;">
                                        <h6 style="font-weight: bold; font-size: 15px; margin:0 ">8.  Delivered to</h6>
                                        <p style="font-size: 13px;margin-bottom: 0"> Name:
                                                <span><?php
                                                    if($data->client_id>0 and $data->client_name!=null){
                                                        echo $data->client_name." ".$data->client_surname."| 1".str_pad($data->client_id, 6, "0", STR_PAD_LEFT);
                                                    }elseif($data->person_id>0 and $data->person_name!=null){
                                                        echo $data->person_name." ".$data->person_surname."| ".$data->person_id;
                                                    }
                                                    else{
                                                        echo $data->name." ".$data->surname ." | ". $data->uniqid;
                                                    }
                                                    ?></span>
                                        </p>
                                        <p style="font-size: 13px;display: inline-block; width: 49%; "> Time:
                                                <span>{{date('H:i')}}</span>
                                        </p>
                                        <p style="font-size: 13px;display: inline-block; width: 49%; "> Date:
                                                <span>{{date('d-m-Y')}}</span>
                                        </p>
                                </div>
                        </div>
                </div>
        </div>
</div>
</body>
</html>
