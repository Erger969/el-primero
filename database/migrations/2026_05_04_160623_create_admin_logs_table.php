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
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // Ej: "suspension", "post_hide", "career_created"
            $table->string('model_type')->nullable(); // Ej: "App\Models\User"
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('details')->nullable(); // Información extra en JSON o string
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
