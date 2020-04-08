@extends('layouts.app')
@section('title', 'Payment')
@section('invoice', 'active')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h5 style="margin-bottom: 0px;">Invoice Payment
                    </h5>
                </div>
                <div class="col-md-2 pull-right">
                </div>
            </div>
        </div>
    </div>
    <form action="{{route('invoicePayment.store', $invoice->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card card-default">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <input type="hidden" value="{{ $invoice->id }}" name="invoice_id"
                               class="form-control invoiceId" readonly>
                        @if($errors->has('id'))
                            <span class="text-danger">{{ $errors->first('id') }}</span>
                        @endif
                        <div class="form-group">
                            <label for="invoice_no">Invoice No</label>
                            <input type="text" value="{{ $invoice->code }}" name="code"
                                   class="form-control code" id="code" readonly>
                            @if($errors->has('code'))
                                <span class="text-danger">{{ $errors->first('code') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Customer</label>
                            <input type="text" name="customer_name" id="customers"
                                   class="form-control customers"
                                   value="{{ $invoice->customer_name }}" readonly>
                            @if($errors->has('customer_name'))
                                <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            @php
                                $invoice_item_amount = 0
                            @endphp
                            @foreach($invoice->invoice_items as $invoice_item)
                                @php
                                    $invoice_item_amount += $invoice_item->amount;
                                @endphp
                            @endforeach
                            <label>Amount</label>
                            <input type="text" name="amount" id="amount" value="{{ $invoice_item_amount }}"
                                   class="form-control amount" readonly>
                            @if($errors->has('amount'))
                                <span class="text-danger">{{ $errors->first('amount') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            @php
                                $subtotal = $invoice->subtotal;
                                $total = $invoice->total;
                                $totalDiscount = $subtotal - $total;
                            @endphp
                            <label>Discount Amount</label>
                            <input type="text" name="discount" id="discount" value="{{ 'Rs. ' .$totalDiscount }}" class="form-control discount"
                                       readonly>
                            @if($errors->has('discount'))
                                <span class="text-danger">{{ $errors->first('discount') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label for="invoice_date">Payment Date</label>
                            <input type="text" class="form-control invoice_date" name="date" value=""
                                   id="invoice_date" readonly>
                            <script>
                                let today = new Date();
                                let dd = String(today.getDate()).padStart(2, '0');
                                let mm = String(today.getMonth() + 1).padStart(2, '0');
                                let yyyy = today.getFullYear();
                                let date = document.getElementById('invoice_date');
                                today = yyyy + '-' + mm + '-' + dd;
                                date.value = today;
                            </script>
                            @if($errors->has('date'))
                                <span class="text-danger">{{ $errors->first('date') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="payment_type">Payment Type</label>
                            <select name="payment_type" id="paymentType" class="form-control paymentType">
                                <option value="Select Payment Type" selected>Select Payment Type</option>
                                <option value="Full Payment">Full Payment</option>
                                <option value="Partial Payment">Partial Payment</option>
                            </select>
                            @if($errors->has('payment_type'))
                                <span class="text-danger">{{ $errors->first('payment_type') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="text" name="total_amount" id="total_amount"
                                   value="{{ $invoice->total }}" class="form-control total_amount" readonly>
                            @if($errors->has('total_amount'))
                                <span class="text-danger">{{ $errors->first('total_amount') }}</span>
                            @endif
                        </div>
                        <div class="form-group" id="addAmount">
                            <label>Add Amount</label>
                            <input type="text" name="add_amount" id="add_amount" class="form-control add_amount"
                                   onkeyup="payment();" onkeypress="return onlynumbers(event);">
                            @if($errors->has('add_amount'))
                                <span class="text-danger">{{ $errors->first('add_amount') }}</span>
                            @endif
                        </div>
                        <?php
                        $due_amount = 0;
                        foreach ($invoice->invoice_payments as $invoice_payment) {
                            $due_amount += $invoice_payment->due_amount;
                        }
                        if ($due_amount) {
                            $due_amt = $due_amount;
                        } else {
                            $due_amt = $invoice->total;
                        }
                        ?>
                        <div class="form-group">
                            <label for="due_amount">Due Amount</label>
                            <input type="text" name="due_amount" id="due_amount" value="{{ $due_amt }}"
                                   class="form-control due_amount" readonly>
                            <input type="hidden" id="default_due_amount" value="{{$due_amt}}">
                            @if($errors->has('due_amount'))
                                <span class="text-danger">{{ $errors->first('due_amount') }}</span>
                            @endif
                        </div>
                        <div class="form-group" id="remainAmount">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary float-right"
                style="margin-right: 30px; margin-bottom: 15px; margin-top: 0px;">Pay
        </button>
    </form>
@endsection
@section('script')
    <script>
        $("#addAmount").hide();
        $("#paymentType").on('change', function () {
            payment();
            if (($(this).val() === "Full Payment") || ($(this).val() === "Select Payment Type")) {
                $("#addAmount").hide();
            } else if ($(this).val() === "Partial Payment") {
                // console.log($("#due_amount").val($("#due_amount").val()));
                $("#addAmount").show();
            }
        });

        function payment() {
            let total_amount = $("#total_amount").val();
            let due_amount = $("#due_amount").val();
            let default_due_amount = $("#default_due_amount").val();

            let add_amount = $("#add_amount").val();
            if ($("#paymentType").val() === "Full Payment") {
                due_amount = 0;
                $("#remainAmount").append().html('<div class="form-group">\n' +
                    '                                    <label for="due_amount">Remaining Amount</label>\n' +
                    '                                    <input type="text" name="remain_amount" id="remain_amount"\n' +
                    '                                           class="form-control remain_amount" readonly>\n' +
                    '                               </div>');
                $("#remain_amount").val(due_amount.toFixed(2));
            } else if ($("#paymentType").val() === "Partial Payment") {
                $("#remainAmount").hide();
                $("#due_amount").val(default_due_amount);
                var due_amt = total_amount - add_amount;
                if (due_amt < 0) {
                    console.log("I was here " + due_amount);
                    $("#add_amount").val(0.00);
                    $("#due_amount").val(default_due_amount);
                    console.log("I was here " + default_due_amount);
                    $.alert({
                        title: 'Alert!',
                        icon: 'fa fa-warning',
                        escapeKey: true,
                        backgroundDismiss: true,
                        content: 'ADDED AMOUNT IS LARGER THAN DUE AMOUNT.'
                    });
                    return false;
                } else {
                    $("#due_amount").val(due_amt.toFixed(2));
                    console.log("I was here 2 " + default_due_amount);
                }
            }
        }

        function onlynumbers(event) {
            let key = window.event ? event.keyCode : event.which;
            if (event.keyCode == 8 || event.keyCode == 46
                || event.keyCode == 37 || event.keyCode == 39) {
                return true;
            }
            else if (key < 48 || key > 57) {
                return false;
            }
            else return true;
        }
    </script>
@endsection