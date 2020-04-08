<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    protected $fillable = [
        'invoice_id', 'invoice_code', 'customer_name', 'payment_date', 'payment_type', 'paid_amount', 'due_amount',
    ];

    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

}
