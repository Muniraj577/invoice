<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_id');
            $table->bigInteger('product_id');
            $table->string('product_code');
            $table->decimal('price', 10, 2);
            $table->integer('qty');
            $table->decimal('amount', 10, 2);
            $table->decimal('subtotal', 10,2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('discount_amount',10,2)->nullable();
            $table->decimal('total', 10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
}
