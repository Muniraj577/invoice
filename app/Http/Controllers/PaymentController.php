<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoicePayment;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index()
    {
        $invoice_payments = InvoicePayment::all();
        return view('payments.index', compact('invoice_payments'));
    }

    public function payment(Request $request, $id)
    {
        $invoice = Invoice::where('id', $id)->with('invoice_items')->with('invoice_payments')->first();
        $due_amount = 0;
        foreach ($invoice->invoice_payments as $invoice_payment) {
            $due_amount += $invoice_payment->due_amount;
        }
        if ($due_amount) {
            return view('invoices.paymentEdit', compact('invoice', $invoice));

        } else {
            return view('invoices.payment', compact('invoice', $invoice));
        }
    }

    public function create()
    {
        //
    }

    public function savePayment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required',
            'code' => 'required',
            'customer_name' => 'required',
            'date' => 'required',
            'payment_type' => 'required',
            'due_amount' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($validator->passes()) {
            $invoice_payment = new InvoicePayment();
            $invoice_payment->invoice_id = $request->invoice_id;
            $invoice_payment->invoice_code = $request->code;
            $invoice_payment->customer_name = $request->customer_name;
            $invoice_payment->payment_date = $request->date;
            $invoice_payment->payment_type = $request->payment_type;
            if ($invoice_payment->payment_type === "Full Payment") {
                $invoice_payment->paid_amount = $request->total_amount;
            } elseif ($invoice_payment->payment_type === "Partial Payment") {
                $invoice_payment->paid_amount = $request->add_amount;
            }
            if ($invoice_payment->payment_type === "Full Payment") {
                $invoice_payment->due_amount = 0;
            } elseif ($invoice_payment->payment_type === "Partial Payment") {
                $invoice_payment->due_amount = $request->due_amount;
            }

            $invoice_payment->save();
        }
        $invoice = Invoice::find($id);
        if ($invoice_payment->due_amount === 0) {
            $invoice->status = "Paid";
        } elseif (!empty($invoice_payment->due_amount)) {
            $invoice->status = "Partially Paid";
        } else {
            $invoice->status = "Unpaid";
        }
        $invoice->save();
//        $notification = array(
//            'message' => 'Payment is successfully made.',
//            'alert-type' => 'success',
//        );
        return redirect()->route('invoices.index')->with('success', 'Payment is successfully made.');

    }

    public function updatePayment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required',
            'code' => 'required',
            'customer_name' => 'required',
            'date' => 'required',
            'payment_type' => 'required',
            'due_amount' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($validator->passes()) {
            $invoice_payment = new InvoicePayment;
            $invoice_payment->invoice_id = $request->invoice_id;
            $invoice_payment->invoice_code = $request->code;
            $invoice_payment->customer_name = $request->customer_name;
            $invoice_payment->payment_date = $request->date;
            $invoice_payment->payment_type = $request->payment_type;
            if ($invoice_payment->payment_type === "Full Payment") {
                $invoice_payment->paid_amount = $request->paid_total_amount;
            } elseif ($invoice_payment->payment_type === "Partial Payment") {
                if (empty($invoice_payment->paid_amount)) {
                    $invoice_payment->paid_amount = $request->add_amount;
                } else {
                    $invoice_payment->paid_amount += $request->add_amount;
                }
            }
            if ($invoice_payment->payment_type === "Full Payment") {
                $invoice_payment->due_amount = 0;
            } elseif ($invoice_payment->payment_type === "Partial Payment") {
                $invoice_payment->due_amount = $request->due_amount;
            }
            $invoice_payment->save();
        }
        $invoice = Invoice::find($id);
        if ($invoice_payment->due_amount === 0) {
            $invoice->status = "Paid";
        } elseif (!empty($invoice_payment->due_amount)) {
            $invoice->status = "Partially Paid";
        } else {
            $invoice->status = "Unpaid";
        }
        $invoice->save();
        return redirect()->route('invoices.index')->with('success', 'Payment updated successfully.');
    }

    public function show($id)
    {
        $invoice = Invoice::find($id);
        $invoice_payment = InvoicePayment::where('id', $id)->first();
        return view('payments.show', compact('invoice_payment', 'invoice', $invoice_payment, $invoice));
    }

    public function paymentPDf($id)
    {
        $invoice = Invoice::find($id);
        $invoice_payment = InvoicePayment::where('id', $id)->first();
        $pdf = PDF::loadView('payments.paymentpdf', compact('invoice', 'invoice_payment'));
        return $pdf->stream('payment.pdf');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
