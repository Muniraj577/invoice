<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<input type="hidden" class="invoice-id" id="invoiceId" value="{{$invoice->id}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="card">
    <div class="card-body">
        <a href="{{route('invoices.edit', $invoice->id)}}" class="btn btn-primary">Edit Invoice</a>
    </div>
</div>
<div class="card mt-3">
    <div class="card-header">
        Create Invoice
        <div class="col-md-2 float-right">
            <button type="button" class="close close-button" aria-label="Close">
                <span aria-hidden="true" class=" ">&times;</span>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form action="{{route('invoices.store')}}" method="POST">
            @csrf
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
                            <input type="text" value="{{$invoice->customer_name}}" name="customer_name"
                                   id="customer_name"
                                   class="form-control customer_name">
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" name="address" id="address" value="{{$invoice->address}}"
                                   class="form-control address">
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
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="text" id="invoice_due_date" value="{{$invoice->due_date}}"
                                   name="due_date"
                                   class="form-control">
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group float-right">
                                    <label for="subtotal"><b>SubTotal</b></label>
                                    <div>{{ $invoice->subtotal }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group float-right">
                                    <label for="discount"><b>Discount</b></label>
                                    <div>{{$invoice->discount}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group float-right">
                                    <label for="total"><b>Total</b></label>
                                    <div>{{$invoice->total}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{--@endsection--}}
{{--@section('script')--}}
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $('.close-button').click(function () {
        for (let i = 2; i <= 7; i++) {
            var column = invoiceList.column(i);
            column.visible(true);
        }
        $('.invoice-table').addClass('col-sm-12').removeClass('col-sm-6')
        $('.invoice-detail-section').addClass('hide');
        $('.invoice-detail-content').html();
    })
</script>
{{--@endsection--}}
