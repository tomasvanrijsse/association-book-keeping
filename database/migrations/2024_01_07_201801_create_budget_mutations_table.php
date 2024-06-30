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
        Schema::create('budget_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id');
            $table->double('amount');
            $table->foreignId('contribution_period_id')->nullable();
            $table->foreignId('bank_transaction_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_mutations');
    }
};
