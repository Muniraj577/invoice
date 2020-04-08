<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice pdf</title>
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
                                <p class="font-weight-bold mb-1">{{$invoice->code}}</p>
                                <p class="text-muted">Due to: {{date('j-F-Y', strtotime($invoice->due_date))}}</p>
                            </div>
                        </div>

                        <hr class="my-5">

                        <div class="row pb-5 p-5">
                            <div class="col-md-6">
                                <p class="font-weight-bold mb-4">Customer Information</p>
                                <p class="mb-1">{{$invoice->customer_name}}</p>
                                {{--<p>Acme Inc</p>--}}
                                <p class="mb-1">{{$invoice->address}}</p>
                                <p class="mb-1">{{$invoice->contact_no}}</p>
                            </div>
                        </div>

                        <div class="row p-5">
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 40px;" class="border-0 text-uppercase small font-weight-bold">Product Name</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Qty</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Price</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invoice->invoice_items as $invoice_item)
                                        <tr>
                                            <td>{{$invoice_item->product->name}}</td>
                                            <td>{{$invoice_item->qty}}</td>
                                            <td>{{$invoice_item->price}}</td>
                                            <td>{{$invoice_item->amount}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex flex-row-reverse bg-dark text-white p-2">
                            <div class="py-2 px-3 text-right" style="text-align: right">
                                <div class="mb-2">Grand Total</div>
                                <div class="h5 font-weight-light">Rs. {{$invoice->total}}</div>
                            </div>

                            <div class="py-2 px-3 text-right" style="text-align: right">
                                <div class="mb-2">Discount</div>
                                @if($invoice->discount_type == "Amount")
                                    <div class="h5 font-weight-light">Rs. {{$invoice->discount}}</div>
                                @else
                                    <div class="h5 font-weight-light">{{$invoice->discount}}%</div>
                                @endif
                            </div>

                            <div class="py-2 px-3 text-right" style="text-align: right">
                                <div class="mb-2">SubTotal</div>
                                <div class="h5 font-weight-light">Rs. {{$invoice->subtotal}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
