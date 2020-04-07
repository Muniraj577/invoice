<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'product_id', 'product_code', 'price', 'qty', 'amount', 'subtotal', 'discount', 'total', 'discount_type'
    ];

    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
