<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar los campos que faltan
            $table->string('lastname')->after('name');
            $table->string('foto_perfil')->nullable()->after('password');
            $table->text('descripcion')->nullable()->after('foto_perfil');
            $table->foreignId('career_id')->nullable()->after('descripcion')->constrained('careers')->onDelete('set null');
            $table->tinyInteger('role_id')->default(1)->after('career_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lastname', 'foto_perfil', 'descripcion', 'career_id', 'role_id']);
        });
    }
};