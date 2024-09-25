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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('entry_id')->nullable();
            $table->double('amount');
            $table->string('related_party_name')->nullable();
            $table->string('related_party_account')->nullable();
            $table->date('date');
            $table->string('description')->nullable();
            $table->string('type');
            $table->foreignId('contribution_period_id')->nullable()->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
