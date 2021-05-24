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
            $table->foreignId('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('client_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->string('sending_type');
            $table->dateTimeTz('sending_date');
            $table->string('recurring_period');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('invoice_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('invoice_id')->references('id')->on('invoices')->cascadeOnDelete();
            $table->boolean('is_paid');
            $table->string('item_name');
            $table->integer('quantity');
            $table->decimal('amount');
            $table->dateTimeTz('last_mailing_time');
            $table->integer('mailing_count');
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
        Schema::dropIfExists('invoice_histories');
        Schema::dropIfExists('invoices');
    }
}
