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
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'description')) {
                // If using SQLite older versions, renameColumn might be tricky, but Laravel 10 supports it.
                // However, drop/add is safer if description is empty or we don't care.
                // Let's try rename first.
                $table->renameColumn('description', 'category');
            } elseif (!Schema::hasColumn('expenses', 'category')) {
                $table->string('category')->after('expense_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'category')) {
                $table->renameColumn('category', 'description');
            }
        });
    }
};
