<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'code', 'customer_name', 'contact_no', 'address', 'status',
        'date', 'due_date', 'subtotal','discount', 'total', 'discount_type',
    ];

    public function invoice_items()
    {
        return $this->hasMany('App\InvoiceItem', 'invoice_id', 'id');
    }
}
