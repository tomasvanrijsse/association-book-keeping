<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contribution_periods', function (Blueprint $table) {
            $table->integer('month')->after('title');
            $table->integer('year')->after('month');
        });
    }

    public function down(): void
    {
        Schema::table('contribution_periods', function (Blueprint $table) {
            $table->dropColumn('month');
            $table->dropColumn('year');
        });
    }
};
