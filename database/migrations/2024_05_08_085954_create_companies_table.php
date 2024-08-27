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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('birthdate');
            $table->string('birthplace');
            $table->string('nationality');
            $table->string('id_number')->unique();
            $table->string('place');
            $table->string('personal_number');
            $table->string('trade_name');
            $table->string('category');
            $table->string('money_head');
            $table->string('bank_name');
            $table->string('licenseـnumber');
            $table->string('tax_number');
            $table->string('address');
            $table->string('mobile');
            $table->string('email');
            $table->string('commercial_number');
            $table->string('jihad_isdar');
            $table->string('active_circle');
            $table->string('isdar_date');
            $table->string('isadarـduration');
            $table->string('type');
            $table->tinyInteger('market_confirm')->default('0');
            $table->tinyInteger('money_confirm')->default('0');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
