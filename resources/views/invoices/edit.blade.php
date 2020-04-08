@extends('layouts.app')
@section('title', 'Invoice')
@section('invoice', 'active')
@section('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
@section('content')

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <div class="card">
        <div class="card-header">Create Invoice</div>
        <div class="card-body">
            <form action="{{route('invoices.update', $invoice->id)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="code">Invoice Code:</label>
                                <input type="text" name="code" id="code" value="{{$invoice->code}}"
                                       class="form-control code">
                            </div>
                            <div class="form-group">
                                <label for="name">Customer Name:</label>
                                <input type="text" name="customer_name" id="customer_name"
                                       class="form-control customer_name" value="{{$invoice->customer_name}}">
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" name="address" id="address"
                                       value="{{$invoice->address}}" class="form-control address">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="phone">Contact No:</label>
                                <input type="text" name="phone" id="phone" value="{{$invoice->contact_no}}"
                                       class="form-control phone">
                            </div>
                            <div class="form-group">
                                <label for="date">Invoice Date</label>
                                <input type="text" id="invoice_date" name="date" value="{{$invoice->date}}
                                        " class="form-control datepicker">
                                @if($errors->has('date'))
                                    <span class="text-danger">{{ $errors->first('date') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="text" id="invoice_due_date" value="{{$invoice->due_date}}" name="due_date"
                                       class="form-control">
                                @if($errors->has('due_date'))
                                    <span class="text-danger">{{ $errors->first('due_date') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr class="mt-3">
                    <div class="row mt-5">
                        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                            <table class="table table-bordered" id="main_table">
                                <thead>
                                <tr>
                                    <th scope="col">PRODUCT NAME</th>
                                    <th scope="col">QTY</th>
                                    <th scope="col">PRICE</th>
                                    <th scope="col">AMOUNT</th>
                                    <th scope="col">
                                        <button type="button" class=" btn btn-success addRow">Add More
                                        </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($invoice->invoice_items as $invoice_item)
                                    <tr>
                                        <td>
                                            <select name="product_id[]" class="form-control productname" id="row_0">
                                                <option>Select Product Name</option>
                                                <option value="{{$invoice_item->product->id}}" {{ $invoice_item->product->name ? 'selected': '' }}>{{$invoice_item->product->name}}</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="qty[]" value="{{$invoice_item->qty}}"
                                                   class="form-control qty" id="qty"
                                                   onkeyup="onQtyChange($(this));"
                                                   onkeypress="return onlynumbers(event);">
                                        </td>
                                        <td><input type="text" name="price[]" id="price"
                                                   value="{{$invoice_item->price}}" class="form-control price"
                                                   onkeyup="onRateChange($(this));" readonly></td>
                                        <td><input type="text" name="amount[]" id="amount"
                                                   value="{{$invoice_item->amount}}" class="form-control amount"
                                                   readonly>
                                        </td>
                                        <td><a class="btn btn-danger remove" onclick="deleteRow();">X</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group float-right">
                                        <label for="subtotal"><b>SubTotal</b></label>
                                        <input type="text" name="subtotal" value="{{$invoice->subtotal}}" id="subTotal"
                                               class="subTotal" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group float-right">
                                        <label for="discount"><b>Discount</b></label>
                                        <input type="text" name="discount" id="discount" value="{{$invoice->discount}}"
                                               class="discount"
                                               onkeyup="amount_discount();"
                                               onkeypress="return onlynumbers(event);">
                                        <select class="" id="amount_symbol" name="amount_symbol">
                                            <option value="Amount" {{ $invoice->discount_type == "Amount" ? 'selected': '' }}>
                                                Amount
                                            </option>
                                            <option value="Percent" {{ $invoice->discount_type == "Percent" ? 'selected': '' }}>
                                                Percent
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group float-right">
                                        <label for="total"><b>Total</b></label>
                                        <input type="text" name="total" value="{{$invoice->total}}" id="total"
                                               class="total" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group float-right">
                                        <button type="submit" class="btn btn-success" id="saveInvoice">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        let selectedList = [];
        let count = 0;
        Array.prototype.equals = function (array) {
            if (!array)
                return false;
            if (this.length != array.length)
                return false;

            for (var i = 0, l = this.length; i < l; i++) {
                if (this[i] instanceof Array && array[i] instanceof Array) {
                    if (!this[i].equals(array[i]))
                        return false;
                }
                else if (this[i] != array[i]) {
                    return false;
                }
            }
            return true;
        };

        function updateSelectedList() {
            selectedList = [];
            let selectedValue;
            $('.productname').each(function () {
                selectedValue = $(this).find('option:selected').val();
                if (selectedValue != "" && $.inArray(selectedValue, selectedList) == "-1") {
                    selectedList.push(selectedValue);
                }
            });
        }

        function disableAlreadySelected() {
            $('option').each(function () {
                if ($.inArray(this.value, selectedList) != "-1") {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        }

        $('tbody').on('change', '.productname', function () {
            let tr = $(this).parent().parent();
            tr.find('.qty').focus();
        });
        $('tbody').on('change', '.productname', function () {
            console.log('Triggered');
            $('.addRow').prop('disabled', false);
            let tr = $(this).parent().parent();
            let id = tr.find('.productname').val();
            let dataId = {'id': id};
            $.ajax({
                type: 'GET',
                url: '{{ url('/findPrice') }}',
                dataType: 'json',
                data: {'id': id},
                success: function (data) {
                    tr.find('.price').val(data.price);
                    tr.find('.code').val(data.code);
                }
            });
        });

        function onQtyChange(qty) {
            let qty_value = qty.val();
            let tr = qty.parents('tr').get();
            if (qty_value) {
                if (qty_value == 0) {
                    $(tr).find('input.amount').val('');
                } else {
                    let price = $(tr).find('input.price').val();
                    let amount = qty_value * price;
                    $(tr).find('input.amount').val(amount.toFixed(2));
                    totalAmount();
                }
            } else {
                $(tr).find('input.amount').val('');
            }
            totalAmount();
        }


        function onRateChange(rate) {
            let tr = rate.parents('tr').get();
            onQtyChange($(tr).find('#qty'));
        }

        function totalAmount() {
            let table_tbody = $('table#main_table tbody');
            let amounts = table_tbody.find('.amount');
            let total_amount = 0;
            $.each(amounts, function (key, value) {
                if ($(value).val()) {
                    total_amount += parseInt($(value).val());
                }
            });
            $('#subTotal').val(total_amount);
            $("#total").val(total_amount);
            amount_discount();
        }

        function amount_discount() {
            var discount_amount = $("#discount").val();
            var amount_symbol = $('#amount_symbol');
            if ($(amount_symbol).val() == "Percent") {
                if ($("#discount").val() > 100) {
                    $("#discount").val(0.00);
                    $("#total").val($("#subTotal").val());
                    $.alert({
                        title: 'Alert!',
                        theme: 'modern',
                        icon: 'fa fa-warning',
                        content: 'DISCOUNT PERCENT MORE THAN 100%!',
                    });
                    totalAmount();
                    return false;
                }
                var total_amount = $("#subTotal").val() - (($("#subTotal").val() * discount_amount) / 100);
            } else if ($(amount_symbol).val() == "Amount") {
                var total_amount = $("#subTotal").val() - discount_amount;
            }
            if (total_amount < 0) {
                $("#discount").val(0.00);
                $("#total").val($("#subTotal").val());
                $.alert({
                    title: 'Alert!',
                    icon: 'fa fa-warning',
                    content: 'DISCOUNT AMOUNT LARGER THAN ACTUAL AMOUNT!',
                });
                return false;
            }
            $("#total").val(total_amount);
        }

        $('#amount_symbol').on('change', function () {
            totalAmount();
        });

        $('tbody').on('change', '.productname', function () {
            // count++;
            updateSelectedList();
            disableAlreadySelected(count);
        })

        $('.addRow').on('click', function () {
            event.preventDefault();
            count++;
            addRow(count);
            updateSelectedList();
            disableAlreadySelected(count);
        });

        function addRow(x) {
            let addRow = '<tr>\n' +
                '<td>\n' +
                '    <select name="product_id[]" class="form-control productname" id="row_' + x + '">\n' +
                '        <option>Select Product Name</option>\n' +
                '        @foreach($products as $product)\n' +
                '            <option value="{{$product->id}}">{{$product->name}}</option>\n' +
                '        @endforeach\n' +
                '    </select>\n' +
                '</td>\n' +
                '<td><input type="text" name="qty[]" class="form-control qty" id="qty" onkeyup="onQtyChange($(this));" onkeypress="return onlynumbers(event);"></td>\n' +
                '<td><input type="text" name="price[]" id="price" class="form-control price" onkeyup="onRateChange($(this));" readonly></td>\n' +
                '<td><input type="text" name="amount[]" id="amount" class="form-control amount" readonly></td>\n' +
                '<td><a class="btn btn-danger remove" onclick="deleteRow();">X</a></td>\n' +
                '                        </tr>';
            $('tbody').append(addRow);
        }

        let selectedField;
        $('.productname').each(function () {
            selectedField = $(this).find('option:selected').val();
            if (selectedField == "Select Product Name") {
                $('.addRow').prop('disabled', true);
            } else {
                $('.addRow').prop('disabled', false);
            }
        });

        function deleteRow() {
            $('tbody').on('click', 'a.remove', function () {
                let count = 0;
                let l = $('tbody tr').length;
                if (l == 1) {
                    $(this).parent().parent().remove();
                    $('.addRow').prop('disabled', false);
                    totalAmount();
                } else {
                    $(this).parent().parent().remove();
                    updateSelectedList();
                    disableAlreadySelected(count);
                    totalAmount();
                }
            });
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

        $(function () {
            $("#invoice_date").datepicker({
                dateFormat: 'yy-mm-dd',
                onSelect: function (_date) {
                    var myDate = $(this).datepicker('getDate'); // Retrieve selected date
                    // console.log(myDate);
                    myDate.setDate(myDate.getDate() + 15); // Add 15 days
                    $('#invoice_due_date').val($.datepicker.formatDate('yy-mm-dd', myDate));
                }
            });
        });

    </script>
@endsection
@section('script')
    {{--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>--}}
    {{--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
@endsection