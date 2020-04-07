<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoiceItem;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index()
    {
        //
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
//        dd($request->all());
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

    public function show(Invoice $invoice)
    {
        //
    }

    public function edit(Invoice $invoice)
    {
        //
    }

    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    public function destroy(Invoice $invoice)
    {
        //
    }
}
