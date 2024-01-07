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
        Schema::create('budget_mutation', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('target_budget_id');
            $table->double('amount');
            $table->date('date');
            $table->uuid('contribution_period_id');
            $table->uuid('bank_transaction_id');
            $table->uuid('source_budget_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('target_budget_id')->references('id')->on('budgets');
            $table->foreign('contribution_period_id')->references('id')->on('contribution_periods');
            $table->foreign('bank_transaction_id')->references('id')->on('bank_transactions');
            $table->foreign('source_budget_id')->references('id')->on('budgets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_mutation');
    }
};
