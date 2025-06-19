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
        // Gunakan nama tabel 'users' untuk konsistensi
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['Admin', 'SuperAdmin', 'Customer'])->default('Customer');
            $table->boolean('status')->default(1); // Pastikan kolom status memiliki default value
            $table->string('password');
            $table->string('phone', 13);
            $table->string('picture')->nullable();
            $table->rememberToken(); // Untuk fitur remember me
            $table->timestamps();
        });

        // Tabel untuk sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users'); // Gunakan nama tabel yang benar
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
