@extends('layouts.app')
@section('title', 'Invoice')
@section('invoice', 'active')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped wrap display table-responsive-xl mt-lg-5" id="invoice"
                   style="width:100%">
                <thead>
                <tr>
                    <th>Invoice Id</th>
                    <th>Invoice No</th>
                    <th>Customer Name</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('script')
    {{--<script src="https://code.jquery.com/jquery-3.3.1.js"></script>--}}
    <script>
        $(document).ready(function () {
            $("#invoice").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ url('/get-invoices') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "id"},
                    {"data": "code"},
                    {"data": "customer_name"},
                    {"data": "qty"},
                    {"data": "subtotal"},
                    {"data": "discount"},
                    {"data": "total"},
                    {"data": "status"},
                ]
            });
        });
    </script>
@endsection