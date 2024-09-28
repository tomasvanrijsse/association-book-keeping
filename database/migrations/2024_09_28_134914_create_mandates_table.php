<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mandates', function (Blueprint $table) {
            $table->id();

            $table->string('external_id');
            $table->string('related_party_name');
            $table->foreignId('budget_id')->nullable()->constrained();

            $table->timestamps();
        });
    }
};
