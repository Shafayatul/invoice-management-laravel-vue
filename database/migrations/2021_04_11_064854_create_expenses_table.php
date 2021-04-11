<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('category_id')->references('id')->on('payment_categories')->cascadeOnDelete();
            $table->foreignId('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->dateTimeTz('expense_date')->nullable(true);
            $table->string('expense_type')->nullable(true);
            $table->decimal('expense_amount', 8,2);
            $table->string('bills_file')->nullable(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('expense_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->references('id')->on('payment_categories')->cascadeOnDelete();
            $table->foreignId('expense_id')->references('id')->on('expenses')->cascadeOnDelete();
            $table->dateTimeTz('expense_date')->nullable(true);
            $table->string('expense_type')->nullable(true);
            $table->decimal('expense_amount', 8,2);
            $table->string('bills_file')->nullable(true);
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
        Schema::dropIfExists('expense_details');
        Schema::dropIfExists('expenses');
    }
}
