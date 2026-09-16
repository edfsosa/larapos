<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->bigInteger('subtotal');
            $table->bigInteger('tax')->default(0);
            $table->bigInteger('total');
            $table->enum('payment_method', ['cash', 'card', 'mixed']);
            $table->bigInteger('amount_received')->nullable();
            $table->bigInteger('change')->nullable();
            $table->string('reference')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
