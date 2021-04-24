<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('client_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->references('id')->on('payment_categories')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->references('id')->on('invoices')->cascadeOnDelete();
            $table->decimal('income_amount', 8,2)->nullable(true);
            $table->string('income_type')->nullable(true);
            $table->text('income_details')->nullable(true);
            $table->string('receipt_file')->nullable(true);
            $table->softDeletes();
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
        Schema::dropIfExists('incomes');
    }
}
