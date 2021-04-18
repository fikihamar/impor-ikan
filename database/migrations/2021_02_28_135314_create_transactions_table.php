<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->text('code');
            $table->dateTime('date')->useCurrent();
            $table->text('address');
            $table->text('proof_image');
            $table->enum('status', ['WP', 'CP', 'OP', 'SO', 'OA']);
            $table->text('note')->nullable();
            $table->foreignId('merchant_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('client_id')
                ->constrained()
                ->onDelete('cascade');
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
        Schema::dropIfExists('transactions');
    }
}
