<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoiceItem;
use App\Product;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('invoices.index');
    }

    public function getInvoiceAndInvoiceItems($domain = '', Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'code',
            2 => 'customer_name',
            3 => 'qty',
            4 => 'subtotal',
            5 => 'discount',
            6 => 'total',
            7 => 'paid_amount',
            8 => 'due_amount',
            9 => 'status',
            10 => 'id',
        );

        $totalData = Invoice::with('invoice_items')->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $invoices = Invoice::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
        } else {
            $search = $request->input('search.value');

            $invoices = Invoice::with('invoice_items')
            ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->select('invoices.*', 'invoice_items.qty', 'invoice_items.amount', 'invoice_items.discount', 'invoice_items.discount_type')
            ->where('invoices.id', 'LIKE', "%{$search}%")
            ->orWhere('invoices.code', 'LIKE', "%{$search}%")
            ->orWhere('invoices.customer_name', 'LIKE', "%{$search}%")
            ->orWhere('invoice_items.qty', 'LIKE', "%{$search}%")
            ->orWhere('invoice_items.amount', 'LIKE', "%{$search}%")
            ->orWhere('invoice_items.discount', 'LIKE', "%{$search}%")
            ->orWhere('invoices.discount', 'LIKE', "%{$search}%")
            ->orWhere('invoices.total', 'LIKE', "%{$search}%")
            ->orWhere('invoices.status', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

            $totalFiltered = Invoice::with('invoice_items')
            ->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->select('invoices.*', 'invoice_items.qty', 'invoice_items.amount', 'invoice_items.discount', 'invoice_items.discount_type')
            ->where('invoices.id', 'LIKE', "%{$search}%")
            ->orWhere('invoices.code', 'LIKE', "%{$search}%")
            ->orWhere('invoices.customer_name', 'LIKE', "%{$search}%")
            ->orWhere('invoice_items.qty', 'LIKE', "%{$search}%")
            ->orWhere('invoice_items.amount', 'LIKE', "%{$search}%")
            ->orWhere('invoice_items.discount', 'LIKE', "%{$search}%")
            ->orWhere('invoices.discount', 'LIKE', "%{$search}%")
            ->orWhere('invoices.total', 'LIKE', "%{$search}%")
            ->orWhere('invoices.status', 'LIKE', "%{$search}%")
            ->count();
        }

        $data = array();

        if (!empty($invoices)) {
            foreach ($invoices as $invoice) {
                $invoice_item_qty = 0;
                $invoice_item_amount = 0;
                $invoice_item_discount = 0;
                $subtotal = $invoice->subtotal;
                $total = $invoice->total;
                $totalDiscount = $subtotal - $total;
                foreach ($invoice->invoice_items as $invoice_item) {
                    $invoice_item_qty += $invoice_item->qty;
                    $invoice_item_amount += $invoice_item->amount;
                    $invoice_item_discount += $invoice_item->discount;
                }
                $show = route('invoices.show', $invoice->id);
                $payment = route('invoices.payment', $invoice->id);
                $nestedData['id'] = "<span data-url = '{$show}' class='detail-tab' onclick='updatePage(this)'>{$invoice->id}</span>";
                $nestedData['code'] = "<span data-url = '{$show}' class='detail-tab' onclick='updatePage(this)' style='color:blue;'>{$invoice->code}</span>";
                $nestedData['customer_name'] = "<span data-url = '{$show}' class='detail-tab' onclick='updatePage(this)' style='color:blue;'>{$invoice->customer_name}</span>";
                $nestedData['qty'] = $invoice_item_qty;
                $nestedData['subtotal'] = $invoice->subtotal;
                $nestedData['discount'] = "Rs. " . $totalDiscount;
                $nestedData['total'] = $invoice->total;
                $nestedData['status'] = $invoice->status;

                $due_amount = 0;
                $paid_amount = 0;
                foreach ($invoice->invoice_payments as $invoice_payment) {
                    $paid_amount += $invoice_payment->paid_amount;
                    $due_amount = $total - $paid_amount;
                }
                if ($due_amount || abs($due_amount) === 0.00) {
                    $nestedData['due_amount'] = $due_amount;
                    $nestedData['paid_amount'] = $paid_amount;
                } else {
                    $nestedData['due_amount'] = $invoice->total;
                    $nestedData['paid_amount'] = 0;
                }
                if ($nestedData['status'] == 'Canceled') {
                    $nestedData['action'] = "<button type='button' onclick='deleteInvoice({$invoice->id})' class='btn btn-danger'>Delete</button>";
                } else {
                    if ($due_amount) {
                        $nestedData['action'] = "<a href='{$payment}'><button type='button' class='btn btn-primary'>Payment</button></a>";
                    } elseif (abs($due_amount) === 0.00) {
                        $nestedData['action'] = "<button type='button' onclick='alertMessage()' class='btn btn-primary'>Payment</button>";
                    } elseif (empty($due_amount)) {
                        $nestedData['action'] = "<a href='{$payment}'><button type='button' class='btn btn-primary'>Payment</button></a>";
                    }
                }
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

    public function findPrice(Request $request)
    {
        $products = Product::select('price', 'code')->where('id', $request->id)->first();
        return response()->json($products);
    }

    public function create()
    {
        $products = Product::all();
        return view('invoices.create', compact('products'));
    }

    public function store(Request $request)
    {
     
     $validator = Validator::make($request->all(), [
        'code' => 'required',
        'customer_name' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'date' => 'required',
    ], [
        'customer_name.required' => 'Customer name is required',
    ]);

     if ($validator->fails()) {
        return redirect()->back()
        ->withErrors($validator)
        ->withInput();
    }

    if ($validator->passes()) {
        $invoice = new Invoice;
        $invoice->code = $request->code;
        $invoice->customer_name = $request->customer_name;
        $invoice->contact_no = $request->phone;
        $invoice->address = $request->address;
        $invoice->subtotal = $request->subtotal;
        $invoice->discount = $request->discount;
        $invoice->discount_type = $request->amount_symbol;
        $invoice->total = $request->total;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->save();
    }
    $invoices = [];
    foreach ($request->input('product_id') as $key => $value) {
        $invoices["product_id.{$key}"] = 'required';
        $invoices["price.{$key}"] = 'required';
        $invoices["qty.{$key}"] = 'required';
        $invoices["amount.{$key}"] = 'required';
    }
    $validator = Validator::make($request->all(), $invoices);
    if ($validator->fails()) {
        return redirect()->back()
        ->withErrors($validator)
        ->withInput();
    }
    if ($validator->passes()) {
        foreach ($request->input("product_id") as $key => $value) {
            $invoice_items = new InvoiceItem;
            $invoice_items->invoice_id = $invoice->id;
            $invoice_items->product_id = collect($request->get("product_id")[$key])->implode(',');
            $invoice_items->product_code = $invoice_items->product->code;
            $invoice_items->price = collect($request->get("price")[$key])->implode(',');
            $invoice_items->qty = collect($request->get("qty")[$key])->implode(',');
            $invoice_items->amount = collect($request->get("amount")[$key])->implode(',');
            $invoice_items->subtotal = $request->get("subtotal");
            $invoice_items->discount = $request->discount;
            $invoice_items->discount_type = $request->amount_symbol;
            $invoice_items->total = $request->get("total");
            $invoice_items->save();
        }
    }
//        $notification = array(
//            'message' => 'Invoice created successfully!',
//            'alert-type' => 'success'
//        );
    return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
}

public function show(Request $request, $id)
{
    $invoice = Invoice::where('id', $id)->with('invoice_items')->first();
    $products = Product::all();
    return view('invoices.show', compact('invoice', 'products', $invoice));
}

public function invoicePage($id)
{
    $invoice = Invoice::where('id', $id)->with('invoice_items')->first();
    $products = Product::all();
    return view('invoices.viewinvoice', compact('invoice', 'products', $invoice));
}

public function edit($id, Request $request)
{
    $invoice = Invoice::where('id', $id)->with('invoice_items')->first();
    $products = Product::all();
    return view('invoices.edit', compact('invoice', 'products', $invoice));
}

public function update(Request $request, $id)
{
    $this->validate($request, [
        'code' => 'required',
        'customer_name' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'date' => 'required',
    ], [
        'customer_name.required' => 'Customer name is required',
    ]);
    $invoice = Invoice::find($id);
    $invoice->code = $request->code;
    $invoice->customer_name = $request->customer_name;
    $invoice->contact_no = $request->phone;
    $invoice->address = $request->address;
    $invoice->subtotal = $request->subtotal;
    $invoice->discount = $request->discount;
    $invoice->discount_type = $request->amount_symbol;
    $invoice->total = $request->total;
    $invoice->date = $request->date;
    $invoice->due_date = $request->due_date;
    $invoice->save();
    $invoice->invoice_items()->delete();

    $invoices = [];
    foreach ($request->input('product_id') as $key => $value) {
        $invoices["product_id.{$key}"] = 'required';
        $invoices["price.{$key}"] = 'required';
        $invoices["qty.{$key}"] = 'required';
        $invoices["amount.{$key}"] = 'required';
    }
    $validator = Validator::make($request->all(), $invoices);
    if ($validator->fails()) {
        return redirect()->back()
        ->withErrors($validator)
        ->withInput();
    }
    if ($validator->passes()) {
        foreach ($request->input("product_id") as $key => $value) {
            $invoice_items = new InvoiceItem;
            $invoice_items->invoice_id = $invoice->id;
            $invoice_items->product_id = collect($request->get("product_id")[$key])->implode(',');
            $invoice_items->product_code = $invoice_items->product->code;
            $invoice_items->price = collect($request->get("price")[$key])->implode(',');
            $invoice_items->qty = collect($request->get("qty")[$key])->implode(',');
            $invoice_items->amount = collect($request->get("amount")[$key])->implode(',');
            $invoice_items->subtotal = $request->get("subtotal");
            $invoice_items->discount = $request->discount;
            $invoice_items->discount_type = $request->amount_symbol;
            $invoice_items->total = $request->get("total");
            $invoice_items->save();
        }
    }
    return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
}

public function invoicePdfDownload($id)
{
    $invoice = Invoice::where('id', $id)->with('invoice_items')->first();
    $pdf = PDF::loadView('invoices.invoicepdf', compact('invoice'));
    return $pdf->stream('invoice.pdf');
}

public function cancelInvoice(Request $request)
{
    Invoice::where('id', $request->invoice_id)->update(['status' => 'Canceled']);
    return response()->json(['success' => 'Invoice is canceled']);
}

public function destroy(Invoice $invoice)
{
        //
}

public function deleteInvoice($id)
{
    $invoice = Invoice::where('id', $id)->with('invoice_items')->first();
    $invoice->invoice_items()->delete();
    $invoice->delete();
    return response()->json(['success', 'Invoice deleted successfully']);
}
}
