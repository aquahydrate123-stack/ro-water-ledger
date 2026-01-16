<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column exists to avoid errors, then modify
        if (Schema::hasColumn('payments', 'sale_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('sale_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting not strictly necessary for this fix, but good practice
        // Warning: This will fail if there are null values
        /*
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('sale_id')->nullable(false)->change();
        });
        */
    }
};
