<?php
use \Milon\Barcode\DNS1D;
?>

<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style>


                .general {
                        position: relative;
                        margin-bottom: 20px;
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
</head><body>
@foreach($invoices as $data)
        <?php

        $barcode_code = "trlmk".$data->id;
        $barcode = DNS1D::getBarcodeSVG($barcode_code, "C128",1.5,64);
        ?>
                        <div class="general">
                                <div class="left-form" style="width:49%;display: inline-block;">
                                        <div class="logo" style="border-bottom: 2px solid black;padding-left: 20px;">
                                                <img src="https://limak.az/tr/eli-logo.png" alt="logo" style="height: 36px">
                                                <p style="margin: 0 0 1px 0; font-size: 12px;">worldwide express logistics</p>
                                        </div>
                                        <div class="left-inner-first" style="border-bottom: 2px solid black">
                                                <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 0">
                                                        <h6 style="font-weight: bold; font-size: 12px; margin:0 ">1. Gonderen / From (Shipper)</h6>
                                                        <div style="width: 49%; display: inline-block">
                                                                Tarih / Date
                                                                <p style="display: inline-block;;margin: 0 0 0 15px;">{{ date('d') }}/{{ date('m') }}/{{ date('Y') }}</p>
                                                        </div>
                                                        <div style="width: 49%; display: inline-block">
                                                                Abone Hesap No. <br> Account Number
                                                                <p style="margin:0 0 0 15px;display:inline-block;"> </p>
                                                        </div>
                                                </div>
                                                <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 0">
                                                        <div style="width: 49%; display: inline-block;">
                                                                Gonderenin adi <br> Shippers's name
                                                                <p style="margin: 0 0 0 15px;display: inline-block"> </p>
                                                        </div>
                                                        <div style="width: 49%; display: inline-block; margin-top: 0">
                                                                Telefon <br> Phone
                                                                <p style="margin: 0 0 0 15px; display: inline-block;">{{ $data->phone }}</p>
                                                        </div>
                                                </div>
                                                <div style="border-bottom: 1px solid black;padding: 5px 0;margin: 0 5px 0">
                                                        <div style="position: relative">
                                                                <p style="display: inline-block; margin: 0;">Firma Adi <br> Company</p>
                                                                <p style="position: absolute; left: 50%;top: 50%;margin: 0;transform: translate(-50%,-50%);">LIMAK INTERNET HIZMETLERI</p>
                                                        </div>
                                                </div>
                                                <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 0">
                                                        <div style="position: relative">
                                                                <p style="display: inline-block; margin: 0;">Firma Adi <br> Company</p>
                                                                <p style="position: absolute; left: 50%;top: 50%;margin: 0;transform: translate(-50%,-50%);">Halkalı Merkez Mahellesi. Üzümlü SK. 5/7</p>
                                                        </div>
                                                </div>
                                                <div style="border-bottom: 1px solid black;padding: 6px 0;height: 30px;margin: 0 5px 0">
                                                </div>
                                                <div style="border-bottom: 1px solid black;padding: 6px 0;height: 30px;margin: 0 5px 0">
                                                </div>
                                                <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 10px">
                                                        <div style="width:34%; display: inline-block;">
                                                                <p style="display: inline-block; margin: 0;"> Sehir <br>City / State</p>
                                                                <p style="display: inline-block; margin:  0 0 0 15px;">Istanbul</p>
                                                        </div>
                                                        <div style="width:32%; display: inline-block">
                                                                <p style="display: inline-block; margin: 0;">Posta Kodu <br> Zip Code</p>
                                                                <p style="display: inline-block; margin:  0 0 0 15px;">34768</p>
                                                        </div>
                                                        <div style="width: 32%; display: inline-block;">
                                                                <p style="display: inline-block; margin: 0;" >Ulke <br> Country</p>
                                                                <p style="display: inline-block; margin:  0 0 0 15px;">Turkiye</p>
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="left-inner-second" style="border-bottom: 2px solid black">
                                                <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 0">
                                                        <h6 style="font-weight: bold; font-size: 12px; margin:0 0 10px 0 ">2. Alici / To (Recipient's)</h6>
                                                        <div style="width: 57%; display: inline-block;">
                                                                <p style="display: inline-block; margin: 0;">Alicinin adi <br> Name</p>
                                                                <p style="display: inline-block; margin:  0 0 0 15px;">{{ $data->name }} {{ $data->surname }} | {{ $data->uniqid }}</p>
                                                        </div>
                                                        <div style="width: 40%; display: inline-block;">
                                                                <p style="display: inline-block; margin: 0;">Telefon <br> Phone</p>
                                                                <p  style="display: inline-block; margin:  0 0 0 15px;">{{ $data->phone }}</p>
                                                        </div>
                                                </div>
                                                <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 0">
                                                        <div style="position: relative">
                                                                <p style="display: inline-block; margin: 0;">Firma Adi <br> Company</p>
                                                                <p style="position: absolute; left: 50%;top: 50%;margin: 0;transform: translate(-50%,-50%);">LIMAK INTERNET HIZMETLERI</p>
                                                        </div>
                                                </div>
                                                <div style="border-bottom: 1px solid black;padding: 6px 0;margin: 0 5px 0">
                                                        <div style="position: relative">
                                                                <p style="display: inline-block; margin: 0;">Adres <br> Address</p>
                                                                <p style="position: absolute; left: 50%;top: 50%;margin: 0;transform: translate(-50%,-50%);">LIMAK INTERNET HIZMETLERI</p>
                                                        </div>
                                                </div>
                                                <div style="border-bottom: 1px solid black;margin: 0 5px 0; height: 42px;">
                                                        <p style="margin: 0; position: relative;height: 42px;" >
                                                                <span style="position: absolute; left: 50%;top:50%;transform: translate(-50%,-50%);font-weight: bold">ELI cannot deliver to a P.O Box </span>
                                                        </p>
                                                </div>
                                                <div style="border-bottom: 1px solid black;padding: 6px 0;height: 30px;margin: 0 5px 0">
                                                </div>
                                                <div style="border-bottom: 1px solid black;padding: 6px 0; margin: 0 5px 10px">
                                                        <div style="width:30%; display: inline-block">
                                                                <p style="display: inline-block; margin: 0;">Sehir <br>City / State</p>
                                                                <p style="display: inline-block; margin:  0 0 5px 15px;">Baku</p>
                                                        </div>
                                                        <div style="width:40%; display: inline-block">
                                                                <p style="display: inline-block; margin: 0;">Posta Kodu <br> Zip Code</p>
                                                                <p style="display: inline-block; margin: 0 0 5px 15px">
                                                                        <span>AZ1000</span><br>
                                                                        <span>Gereklidir / Required</span>
                                                                </p>
                                                        </div>
                                                        <div style="width: 28%; display: inline-block">
                                                                <p style="display: inline-block; margin: 0;">Ulke <br> Country</p>
                                                                <p style="display: inline-block; margin: 0 0 5px 15px">Azerbaijan</p>
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="left-inner-third">
                                                <div style="padding-top: 5px;margin: 0 5px 0">
                                                        <h6 style="font-weight: bold; font-size: 12px; margin:0 ">3. Gonderenin Onayi (Imzalanmasi Gerekir) / Shipper's Authorization & Signature</h6>
                                                        <p style="font-size: 12px; margin:3px 0 10px;">Gonderen kendisine verilen Gonderen kendisine verilen Gonderen kendisine verilen Gonderen kendisine verilen Gonderen kendisine verilen</p>
                                                        <div style="width: 49%; display: inline-block; margin-top: 0">
                                                                <p style="display: inline-block;margin: 0;">Imza <br> Signature</p>
                                                                <p style="display: inline-block;border-bottom: 1px solid black;height: 30px;width: 71%;margin: 0;"></p>
                                                        </div>
                                                        <div style="width: 49%; display: inline-block; margin-top: 0;position:relative;">
                                                                <p style="display:inline-block;margin: 0;">Telefon <br> Phone</p>
                                                                <p style="display: inline-block;border-bottom: 1px solid black;height: 20px;width: 60%;margin: 0;position: absolute;bottom: 4px;">
                                                                        <span style="margin-left: 20px">0212 470 3737</span>
                                                                </p>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="right-form" style="width: 49%;display: inline-block;padding-left: 20px">
                                        <div style="border-bottom: 2px solid black;padding: 0 20px;display: flex;align-items: center;justify-content: space-between;">
                                                <h2 style="width: 35%;margin-top: 0;text-align: right">{{ $barcode_code." " }}

                                                </h2>

                                                <div class="">
                                                        {!! $barcode !!}
                                                </div>
                                        </div>
                                        <div class="left-inner-fourth" style="border-bottom: 1px solid black">
                                                <div style="padding-top: 5px;margin: 0 5px 0">
                                                        <h6 style="font-weight: bold; font-size: 12px; margin:0 ">4. Uluslararasi Servisler / International Services</h6>
                                                        <div style="display: flex;align-items: center">
                                                                <div class="check-part" style="border: 1px solid black;width: 34%;padding: 2px;display: flex;flex-direction: column;justify-content: center;align-items: center;">
                                                                        <label class="container" style="margin: 5px 0">EXPRESS
                                                                                <input type="checkbox" checked>
                                                                                <span class="checkmark"></span>
                                                                        </label>
                                                                        <p style="font-size: 12px; text-align: center;margin: 0;">It has survived not only five centuries, but also the leap into electronic typesetting </p>
                                                                </div>
                                                                <div class="check-part" style="border: 1px solid black;width: 34%;padding: 2px; margin: 0 10px;display: flex;flex-direction: column;justify-content: center;align-items: center;">
                                                                        <label class="container" style="margin: 5px 0">ECONOMY
                                                                                <input type="checkbox">
                                                                                <span class="checkmark"></span>
                                                                        </label>
                                                                        <p style="font-size: 12px; text-align: center;margin: 0;">It has survived not only five centuries, but also the leap into electronic typesetting</p>
                                                                </div>
                                                                <div class="check-part" style=" display: flex;width: 24%;padding: 5px; height: 90px;align-items: center;justify-content: center">
                                                                        <p style="font-size: 15px; text-align: center;margin:0;">
                                                                                Shipping price <br>
                                                                                <span>{{ $data->shipping_price }}  USD</span> <br>
                                                                                <span>Total:  {{ number_format(($data->price / $tryToUsd) + (float) $data->shipping_price, 2) }} USD</span>
                                                                        </p>
                                                                </div>
                                                        </div>
                                                        <p style="margin: 0;font-size: 12px;">It has survived not only five centuries, but also the leap into electronic </p>
                                                        <p style="margin: 0;font-size: 12px;">***It has survived not only five centuries, but also the leap into electronic </p>
                                                </div>
                                        </div>
                                        <div class="right-inner-fifth" style="border-bottom: 1px solid black;position:relative;">
                                                <h6 style="font-weight: bold; font-size: 15px; margin:5px 0 5px 0 ">5. Tasima Hizmetleri Kime Faturalandirilacak / Transportation Charges Bill to:</h6>
                                                <div style="width: 36%;display: inline-block">
                                                        <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;">Gonderen / Sender
                                                                <input type="checkbox" checked>
                                                                <span class="checkmark"></span>
                                                        </label>
                                                        <div style="position: relative;margin-bottom: 10px">
                                                                <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;display: inline-block">Ucuncu taraf / Third party (acct.)
                                                                        <input type="checkbox">
                                                                        <span class="checkmark"></span>
                                                                </label>
                                                                <p style="display: inline-block;border-bottom: 1px solid black;height: 20px;width: 20%;margin: 0; position:absolute;bottom: 3px;">
                                                                        <span style="margin-left: 10px"></span>
                                                                </p>
                                                        </div>
                                                </div>
                                                <div style="width: 18%;display: inline-block">
                                                        <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;">Alici<br> Recipient
                                                                <input type="checkbox">
                                                                <span class="checkmark"></span>
                                                        </label>
                                                </div>
                                                <div style="width: 44%;display: inline-block">
                                                        <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;">Kredit Karti / Cash / Credit Card
                                                                <input type="checkbox">
                                                                <span class="checkmark"></span>
                                                        </label>
                                                        <div style="position: relative;margin-bottom: 10px">
                                                                <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;display: inline-block">Teslimatta Tahlil Edilecek Tutar / C.O.D.S
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
                                                        <h6 style="font-weight: bold; font-size: 15px; margin:0 ">6. Gonderici Bilgileri / Shipment Information</h6>
                                                        <div style="display: flex;position:relative;">
                                                                <div class="table-one" style="width: 20%;display: inline-block">
                                                                        <table>
                                                                                <tr>
                                                                                        <th>Parca <br> Pieces</th>
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
                                                                                <caption style="font-weight: bold;margin-top: 6px">OLCULER <br> DIMENSIONS</caption>
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
                                                                                <caption style="font-size: 13px;">Toplam / Total</caption>
                                                                                <tr>
                                                                                        <td>Parca <br> Pcs</td>
                                                                                        <td>Agirlik <br> Weight</td>
                                                                                </tr>
                                                                        </table>
                                                                </div>
                                                                <div class="dimen-part" style="display:inline-block;width: 15%;position: relative;bottom: 7px;">
                                                                        <label class="container small" style="margin:0;font-weight: normal;font-size: 13px;">Kilo / Kg
                                                                                <input type="checkbox" checked>
                                                                                <span class="checkmark"></span>
                                                                        </label>
                                                                        <label class="container small" style="margin:0;font-weight: normal;font-size: 13px;">Libre / Lbs
                                                                                <input type="checkbox">
                                                                                <span class="checkmark"></span>
                                                                        </label>
                                                                </div>
                                                                <div style="width: 20%;display: inline-block">
                                                                        <label class="container small" style="margin: 0 0 0 20px;font-weight: normal;font-size: 13px;position: relative;bottom: 7px;">Zarf<br> Envelope
                                                                                <input type="checkbox">
                                                                                <span class="checkmark"></span>
                                                                        </label>
                                                                </div>
                                                                <div style="width: 20%;display: inline-block">
                                                                        <label class="container small" style="margin: 0 0 0 20px;font-weight:normal;font-size: 13px;position: relative;bottom: 7px;">Poset<br> Pack
                                                                                <input type="checkbox" checked>
                                                                                <span class="checkmark"></span>
                                                                        </label>
                                                                </div>
                                                                <div style="width: 20%;display: inline-block">
                                                                        <label class="container small" style="margin: 0 0 0 20px;font-weight: normal;font-size: 13px;position: relative;bottom: 7px;">Koli<br> Box
                                                                                <input type="checkbox">
                                                                                <span class="checkmark"></span>
                                                                        </label>
                                                                </div>
                                                        </div>
                                                        <div class="table-three" style="position:relative; display: flex">
                                                                <ul style="width: 30%;text-align: center;">
                                                                        <li style="border-bottom: 1px solid">
                                                                                Icindekilerin Tam Tanimi <br> Commodity Description
                                                                        </li>
                                                                        <li>
                                                                                {{ $data->product_type_name }}
                                                                        </li>
                                                                        <li></li>
                                                                        <li></li>
                                                                </ul>
                                                                <ul style="width: 22%;text-align: center;border-left: none;">
                                                                        <li style="border-bottom: 1px solid">
                                                                                Harmoni Kodu <br> Harmonized Code
                                                                        </li>
                                                                        <li>
                                                                        </li>
                                                                        <li></li>
                                                                        <li></li>
                                                                </ul>
                                                                <ul style="width: 22%;text-align: center;border-left: none;">
                                                                        <li style="border-bottom: 1px solid">
                                                                                Uretdigi Ulke <br> Country of Manufacture
                                                                        </li>
                                                                        <li>
                                                                                Türkiye
                                                                        </li>
                                                                        <li></li>
                                                                        <li></li>
                                                                </ul>
                                                                <ul style="width: 22%;text-align: center;border-left: none;">
                                                                        <li style="border-bottom: 1px solid">
                                                                                Gomruk Degeri <br> Value  for Customs
                                                                        </li>
                                                                        <li>
                                                                        </li>
                                                                        <li></li>
                                                                        <li></li>
                                                                </ul>
                                                        </div>
                                                        <div class="total-value">
                                                                <ul style="border:none;width: 100%">
                                                                        <li class="total-second" style="height: 30px;float: right; width: 22%; display: inline-block;border-width:0 1px 1px 1px ; border-style: solid;text-align: center ">
                                                                                {{ $data->price }}
                                                                        </li>
                                                                        <li style="width: 22%;display: inline-block; border:none;text-align: center; float: right">

                                                                                Total Value <br>
                                                                                For Customs
                                                                        </li>
                                                                </ul>
                                                        </div>
                                                </div>
                                                <div class="right-inner-seventh" style="width: 46%;display: inline-block;">
                                                        <div class="left-inner-fifth" style="border-width:1px 1px 0 0;border-style: solid;position:relative;margin-top: 10px;padding: 3px 0 15px 3px;">
                                                                <h6 style="font-weight: bold; font-size: 15px; margin:0 0 10px 0 ">7. Ic Referanslar/ Internal Reference</h6>
                                                        </div>
                                                </div>
                                                <div class="right-inner-eight" style="display: flex">
                                                        <div class="left-inner-fifth" style="border-top:1px solid;position:relative;padding: 3px 0 0 3px; width: 30%;display: inline-block;">
                                                                <h6 style="font-weight: bold; font-size: 15px; margin:0 ">8. Secenekler / Options</h6>
                                                                <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;display: inline-block">Sigorta<br> Insurance
                                                                        <input type="checkbox">
                                                                        <span class="checkmark"></span>
                                                                </label>
                                                        </div>
                                                        <div class="left-inner-fifth" style="border-width:1px 0 0 1px;border-style: solid;position:relative;padding: 3px 0 0 3px;width: 68%;display: inline-block;">
                                                                <h6 style="font-weight: bold; font-size: 15px; margin:0 ">9. Harc ve Vergiler Kime Faturalanacak / Duties & Taxes Bill to:</h6>
                                                                <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;display: inline-block">Gonderen<br> Sender
                                                                        <input type="checkbox">
                                                                        <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container small" style="margin: 5px 0 0 20px;font-weight: normal;font-size: 13px;display: inline-block">Alici<br> Recipient
                                                                        <input type="checkbox">
                                                                        <span class="checkmark"></span>
                                                                </label>
                                                        </div>
                                                </div>
                                                <div class="right-inner-ninth" style="display: flex;border-bottom: 1px solid">
                                                        <div class="left-inner-fifth" style="border-top:1px solid;position:relative;padding: 3px 0 0 3px; width: 38%;display: inline-block;">
                                                                <h6 style="font-weight: bold; font-size: 15px; margin:0 ">10. Teslim alan / Picked-Up By</h6>
                                                                <p style="font-size: 13px;margin-bottom: 0">Ad / Name:
                                                                        <span>Musa</span>
                                                                </p>
                                                                <p style="font-size: 13px;display: inline-block; width: 49%; ">Saat / Time:
                                                                        <span>{{date('H:i')}}</span>
                                                                </p>
                                                                <p style="font-size: 13px;display: inline-block; width: 49%; ">Tarih / Date:
                                                                        <span>{{date('d-m-Y')}}</span>
                                                                </p>
                                                        </div>
                                                        <div class="left-inner-fifth" style="border-width:1px 0 0 1px;border-style: solid;position:relative;padding: 3px 0 0 3px;width: 60%;display: inline-block;">
                                                                <h6 style="font-weight: bold; font-size: 15px; margin:0 ">11. Teslim Edilen / Delivered to</h6>
                                                                <p style="font-size: 13px;margin-bottom: 0">Ad / Name:
                                                                        <span>{{ $data->name }} {{ $data->surname }} | {{ $data->uniqid }}</span>
                                                                </p>
                                                                <p style="font-size: 13px;display: inline-block; width: 49%; ">Saat / Time:
                                                                        <span>{{date('H:i')}}</span>
                                                                </p>
                                                                <p style="font-size: 13px;display: inline-block; width: 49%; ">Tarih / Date:
                                                                        <span>{{date('d-m-Y')}}</span>
                                                                </p>
                                                        </div>
                                                </div>
                                                <div>
                                                        <p style="font-size: 13px;">Inquries? Visit our site to www.limak.az or Call 0212 470 37 37</p>
                                                </div>
                                        </div>
                                </div>
                        </div>

@endforeach
</body></html>


