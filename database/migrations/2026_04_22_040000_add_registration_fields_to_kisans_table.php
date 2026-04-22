<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kisans', function (Blueprint $table) {
            $table->string('reg_no', 40)->nullable()->after('id');
            $table->string('legacy_arazi_no', 50)->nullable()->after('reg_no');
            $table->decimal('amount', 12, 2)->nullable()->after('legacy_arazi_no');
            $table->string('location')->nullable()->after('amount');

            $table->unique('reg_no', 'kisans_reg_no_unique');
            $table->index('legacy_arazi_no', 'kisans_legacy_arazi_no_idx');
        });
    }

    public function down(): void
    {
        Schema::table('kisans', function (Blueprint $table) {
            $table->dropUnique('kisans_reg_no_unique');
            $table->dropIndex('kisans_legacy_arazi_no_idx');
            $table->dropColumn(['reg_no', 'legacy_arazi_no', 'amount', 'location']);
        });
    }
};
