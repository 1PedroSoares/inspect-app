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
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();

            // Chave estrangeira para a inspeção
            $table->foreignId('inspection_id')
                ->constrained('inspections')
                ->onDelete('cascade');

            $table->string('descricao');
            $table->boolean('obrigatorio')->default(false);
            $table->boolean('concluido')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_items');
    }
};
