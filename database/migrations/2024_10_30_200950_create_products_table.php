<?php

use App\Models\Category;
use App\Models\User;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->bigInteger('price');
            $table->float('weight');
            $table->integer('stock');
            $table->string('image')->nullable();
            $table->boolean('status')->default(0);
            $table->foreignIdFor(Category::class, 'category_id');
            $table->foreignIdFor(User::class, 'publisher');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
