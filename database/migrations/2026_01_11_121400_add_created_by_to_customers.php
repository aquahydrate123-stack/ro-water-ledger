<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('id');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('customers', 'status')) {
                $table->string('status')->default('active')->after('created_by'); // If created_by exists, this places after it. If not added above, might fail if strict.
                // Safest to just rely on auto placement or check order if critical.
                // Given the if checks, let's assume standard flow.
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn(['created_by']);
            }
            if (Schema::hasColumn('customers', 'status')) {
                $table->dropColumn(['status']);
            }
        });
    }
};
