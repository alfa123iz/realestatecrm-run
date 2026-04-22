<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kisans', function (Blueprint $table) {
            $table->index('mobile', 'kisans_mobile_idx');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->index('mobile', 'agents_mobile_idx');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->index('mobile', 'customers_mobile_idx');
        });

        Schema::table('arazis', function (Blueprint $table) {
            $table->index('status', 'arazis_status_idx');
            $table->index(['kisan_id', 'status'], 'arazis_kisan_status_idx');
            $table->unique(['kisan_id', 'plot_number'], 'arazis_kisan_plot_unique');
        });

        Schema::table('registries', function (Blueprint $table) {
            $table->string('registry_code', 40)->nullable()->unique()->after('id');
            $table->index(['status', 'due_date'], 'registries_status_due_idx');
            $table->index(['payment_status', 'due_date'], 'registries_payment_due_idx');
            $table->index(['customer_id', 'status'], 'registries_customer_status_idx');
            $table->index(['agent_id', 'status'], 'registries_agent_status_idx');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('reference_no', 40)->nullable()->unique()->after('id');
            $table->index(['payment_date', 'payment_type'], 'payments_date_type_idx');
            $table->index('registry_id', 'payments_registry_idx');
            $table->index('customer_id', 'payments_customer_idx');
            $table->index('kisan_id', 'payments_kisan_idx');
        });

        Schema::table('arazi_documents', function (Blueprint $table) {
            $table->index(['arazi_id', 'uploaded_at'], 'arazi_docs_arazi_uploaded_idx');
        });
    }

    public function down(): void
    {
        Schema::table('arazi_documents', function (Blueprint $table) {
            $table->dropIndex('arazi_docs_arazi_uploaded_idx');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_date_type_idx');
            $table->dropIndex('payments_registry_idx');
            $table->dropIndex('payments_customer_idx');
            $table->dropIndex('payments_kisan_idx');
            $table->dropUnique(['reference_no']);
            $table->dropColumn('reference_no');
        });

        Schema::table('registries', function (Blueprint $table) {
            $table->dropIndex('registries_status_due_idx');
            $table->dropIndex('registries_payment_due_idx');
            $table->dropIndex('registries_customer_status_idx');
            $table->dropIndex('registries_agent_status_idx');
            $table->dropUnique(['registry_code']);
            $table->dropColumn('registry_code');
        });

        Schema::table('arazis', function (Blueprint $table) {
            $table->dropIndex('arazis_status_idx');
            $table->dropIndex('arazis_kisan_status_idx');
            $table->dropUnique('arazis_kisan_plot_unique');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_mobile_idx');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->dropIndex('agents_mobile_idx');
        });

        Schema::table('kisans', function (Blueprint $table) {
            $table->dropIndex('kisans_mobile_idx');
        });
    }
};
