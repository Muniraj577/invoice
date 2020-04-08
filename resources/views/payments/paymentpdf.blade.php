<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Pdf</title>
</head>
<body>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        /*margin-right: 20px;*/
        margin-left: 20px;
    }
    th, td {
        padding: 1px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
</style>
<div class="card-body">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row p-5">
                            <div class="col-md-6">
                                Company Logo
                            </div>

                            <div class="col-md-6 text-right" style="text-align: right">
                                <p class="font-weight-bold mb-1">{{$invoice_payment->invoice_code}}</p>
                                <p class="text-muted">Payment Date
                                    : {{date('j-F-Y', strtotime($invoice_payment->payment_date))}}</p>
                                <p class="text-muted">Payment Type: {{$invoice_payment->payment_type}}</p>
                            </div>
                        </div>

                        <hr class="my-5">

                        <div class="row pb-5 p-5">
                            <div class="col-md-6">
                                <p class="font-weight-bold mb-4">Customer Information</p>
                                <p class="mb-1">{{$invoice_payment->customer_name}}</p>
                                {{--<p>Acme Inc</p>--}}
                                <p class="mb-1">{{$invoice_payment->invoice['address']}}</p>
                                <p class="mb-1">{{$invoice_payment->invoice['contact_no']}}</p>
                            </div>
                        </div>
                        <div class="d-flex flex-row-reverse bg-dark text-white p-2">
                            <div class="py-2 px-3 text-right" style="text-align: right">
                                <div class="mb-2">Due Amount</div>
                                <div class="h5 font-weight-light">{{$invoice_payment->due_amount}}</div>
                            </div>
                            <div class="py-2 px-3 text-right" style="text-align: right">
                                <div class="mb-2">Paid Amount</div>
                                <div class="h5 font-weight-light">{{$invoice_payment->paid_amount}}</div>
                            </div>

                            <div class="py-2 px-3 text-right" style="text-align: right">
                                <div class="mb-2">Total</div>
                                <div class="h5 font-weight-light">Rs. {{$invoice_payment->invoice['total']}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>