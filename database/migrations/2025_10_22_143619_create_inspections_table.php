<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('cep', 9);
            $table->string('logradouro');
            $table->string('numero', 5);
            $table->string('bairro')->nullable();
            $table->string('cidade');
            $table->string('uf', 2)->nullable();
            $table->dateTime('data_prevista');
            $table->enum('status', ['Pendente', 'Concluida'])->default('Pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * 
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
