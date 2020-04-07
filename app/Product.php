<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['code', 'name', 'price'];

    public function invoice_items()
    {
        return $this->hasMany('App\InvoiceItem', 'product_id', 'id');
    }
}
