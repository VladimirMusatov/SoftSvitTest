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
        Schema::create('financical_data', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('name');
            $table->double('open');
            $table->jsonb('financial_details');
            $table->double('previousClose');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financical_data');
    }
};
