@extends('layouts.app')
@section('title', 'Payments')
@section('payments', 'active')
@section('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        a{
            color: black;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table dataTable no-footer table-responsive-lg" id="payment_details"
                   style="width: 1089px;">
                <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Invoice Code</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>
                    <th>Paid Amount</th>
                    <th>Payment Type</th>
                    <th>Payment Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice_payments as $invoice_payment)
                    <tr>
                        <td>{{ $invoice_payment->invoice_id }}</td>
                        <td><a href="{{route('payments.show', $invoice_payment->id)}}">{{ $invoice_payment->invoice_code }}</a></td>
                        <td><a href="{{route('payments.show', $invoice_payment->id)}}">{{ $invoice_payment->customer_name }}</a></td>
                        <td>{{$invoice_payment->invoice['total']}}</td>
                        <td>{{ $invoice_payment->paid_amount }}</td>
                        <td>{{ $invoice_payment->payment_type }}</td>
                        <td>{{ $invoice_payment->payment_date }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection