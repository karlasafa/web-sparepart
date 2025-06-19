<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->json('products'); // Menyimpan data produk dalam format JSON
            $table->enum('payment_method', ['Cash', 'Debit', 'QRIS']);
            $table->bigInteger('total');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->text('address')->nullable();
            $table->foreignIdFor(User::class, 'customer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
