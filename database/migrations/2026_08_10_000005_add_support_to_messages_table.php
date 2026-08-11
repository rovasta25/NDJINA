<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('alerte_id')->nullable()->change();
            $table->string('objet')->nullable()->after('destinataire_id');
            $table->boolean('traite')->default(false)->after('objet');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['objet', 'traite']);
            $table->unsignedBigInteger('alerte_id')->nullable(false)->change();
        });
    }
};
