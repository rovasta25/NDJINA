<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('groupe_sanguin_id')->constrained('groupe_sanguins')->cascadeOnDelete();
            $table->unsignedInteger('quantite')->default(1);
            $table->enum('urgence', ['haute', 'moyenne', 'basse'])->default('moyenne');
            $table->string('lieu')->nullable();
            $table->string('ville');
            $table->text('description')->nullable();
            $table->enum('statut', ['active', 'terminee', 'rejetee'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};
