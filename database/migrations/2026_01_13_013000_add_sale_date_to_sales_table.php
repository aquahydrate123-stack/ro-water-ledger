<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'sale_date')) {
                $table->date('sale_date')->nullable()->after('total_amount');
            }
        });

        // Populate sale_date from created_at for existing records
        DB::table('sales')->update(['sale_date' => DB::raw('DATE(created_at)')]);
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('sale_date');
        });
    }
};
