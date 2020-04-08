@extends('layouts.app')
@section('title', 'Invoice')
@section('invoice', 'active')
{{--@section('css')--}}
    {{--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">--}}
{{--@endsection--}}
@section('content')
    <style>
        .hide{
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--<div class="card">--}}
        {{--<div class="card-body">--}}
            {{--<table class="table table-striped wrap display table-responsive-xl mt-lg-5" id="invoice"--}}
                   {{--style="width:100%">--}}
                {{--<thead>--}}
                {{--<tr>--}}
                    {{--<th>Invoice Id</th>--}}
                    {{--<th>Invoice No</th>--}}
                    {{--<th>Customer Name</th>--}}
                    {{--<th>Qty</th>--}}
                    {{--<th>Subtotal</th>--}}
                    {{--<th>Discount</th>--}}
                    {{--<th>Total</th>--}}
                    {{--<th>Status</th>--}}
                {{--</tr>--}}
                {{--</thead>--}}
            {{--</table>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <a href="{{route('invoices.create')}}" class="btn btn-primary">Add Invoice</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 invoice-table">
            <div class="card invoice-list-page">
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
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card invoice-detail-section hide">
                <div class="card-body invoice-detail-content">

                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script>
            invoiceList = $("#invoice").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ url('/get-invoices') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                'columnDefs': [
                    {'className': "column-0", targets: 0},
                    {'className': "column-1", targets: 1},
                    {'className': "column-2", targets: 2},
                    {'className': "column-3", targets: 3},
                    {'className': "column-4", targets: 4},
                    {'className': "column-5", targets: 5},
                    {'className': "column-6", targets: 6},
                    {'className': "column-7", targets: 7},
                    {'className': "column-8", targets: 8},
                    {'className': "column-9", targets: 9},
                    {'className': "column-10", targets: 10},
                ],
                "columns": [
                    {"data": "id"},
                    {"data": "code"},
                    {"data": "customer_name"},
                    {"data": "qty"},
                    {"data": "subtotal"},
                    {"data": "discount"},
                    {"data": "total"},
                    {"data": "paid_amount"},
                    {"data": "due_amount"},
                    {"data": "status"},
                    {"data": "action"},
                ]
            });

        function updatePage(obj) {
            let invoiceUrl = $(obj).data('url');
            $.ajax({
                type: "GET",
                url: invoiceUrl,
                success: function (data) {
                    for (let i = 3; i <= 10; i++) {
                        var column = invoiceList.column(i);
                        column.visible(false);
                    }
                    $('.invoice-table').addClass('col-sm-6').removeClass('col-sm-12');
                    $('.invoice-detail-section').removeClass('hide');
                    $('.invoice-detail-content').html(data);
                }
            });
        }
        function alertMessage() {
            $.alert({
                icon: 'fa fa-smile-o',
                title: 'Paid',
                content: 'This payment is already cleared.<br> Thank You',
                theme: 'modern',
            });
        }
    </script>
@endsection

@section('script')
    {{--<script src="https://code.jquery.com/jquery-3.3.1.js"></script>--}}

@endsection