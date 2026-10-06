<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            // Dono do evento
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });

        Schema::table('perguntas', function (Blueprint $table) {
            // Autor da pergunta e visibilidade
            $table->foreignId('user_id')->nullable()->after('evento_id')->constrained('users')->nullOnDelete();
            $table->boolean('is_public')->default(true)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('perguntas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('is_public');
        });

        Schema::table('eventos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
