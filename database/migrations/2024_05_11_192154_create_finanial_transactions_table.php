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
        Schema::create('finanial_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trans_number')->unique();
            $table->string('trans_price');
            $table->integer('company_id');
            $table->string('trans_type');
            $table->integer('employe_id');
            $table->text('notes')->nullable();
            $table->string('file');
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finanial_transactions');
    }
};
