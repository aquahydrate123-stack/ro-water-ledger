<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('expense_category_id')->nullable()->after('id')->constrained('expense_categories')->onDelete('cascade');
        });

        // Data migration: Create categories from existing 'category' string and link them
        $expenses = DB::table('expenses')->get();
        foreach ($expenses as $expense) {
            if ($expense->category) {
                $categoryId = DB::table('expense_categories')->updateOrInsert(
                    ['name' => $expense->category, 'user_id' => $expense->created_by ?? 1],
                    ['created_at' => now(), 'updated_at' => now()]
                );

                $category = DB::table('expense_categories')
                    ->where('name', $expense->category)
                    ->where('user_id', $expense->created_by ?? 1)
                    ->first();

                if ($category) {
                    DB::table('expenses')
                        ->where('id', $expense->id)
                        ->update(['expense_category_id' => $category->id]);
                }
            }
        }

        Schema::table('expenses', function (Blueprint $table) {
            // Requirement says "Each expense entry must belong to a category"
            // But we might want to allow null if we don't have a category for old ones?
            // Let's make it required for new ones.
            $table->foreignId('expense_category_id')->nullable(false)->change();

            if (Schema::hasColumn('expenses', 'category')) {
                $table->dropColumn('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('category')->nullable()->after('expense_category_id');
            $table->dropForeign(['expense_category_id']);
            $table->dropColumn('expense_category_id');
        });
    }
};
