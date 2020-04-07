<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('customer_name');
            $table->string('contact_no');
            $table->text('address')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->dateTime('date');
            $table->dateTime('due_date')->nullable();
            $table->enum('status', ['Paid', 'Unpaid', 'Partially Paid']);
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
        Schema::dropIfExists('invoices');
    }
}
