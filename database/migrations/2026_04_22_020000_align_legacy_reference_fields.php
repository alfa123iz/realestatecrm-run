<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('secondary_mobile', 20)->nullable()->after('mobile');
            $table->string('id_document_no', 60)->nullable()->after('secondary_mobile');
            $table->string('legacy_customer_code', 40)->nullable()->unique()->after('id');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->string('form_code', 30)->nullable()->unique()->after('id');
            $table->string('rank_title', 60)->nullable()->after('name');
            $table->decimal('legacy_percent', 5, 2)->nullable()->after('commission_percentage');
            $table->foreignId('sponsor_agent_id')->nullable()->after('form_code')->constrained('agents')->nullOnDelete();
        });

        Schema::table('arazis', function (Blueprint $table) {
            $table->string('legacy_arazi_code', 50)->nullable()->after('id');
            $table->string('block', 20)->nullable()->after('plot_number');
            $table->string('plot_type', 50)->nullable()->after('block');
            $table->index('legacy_arazi_code', 'arazis_legacy_code_idx');
        });

        Schema::table('registries', function (Blueprint $table) {
            $table->string('customer_reg_no', 40)->nullable()->unique()->after('registry_code');
            $table->enum('booking_mode', ['cash', 'emi', 'mixed', 'other'])->default('other')->after('registry_date');
            $table->decimal('installment_amount', 12, 2)->nullable()->after('advance_amount');
            $table->decimal('down_payment', 12, 2)->nullable()->after('installment_amount');
            $table->enum('lock_status', ['unlock', 'lock'])->default('unlock')->after('payment_status');
            $table->string('nominee_name', 150)->nullable()->after('witness_name');
            $table->foreignId('check_by_agent_id')->nullable()->after('agent_id')->constrained('agents')->nullOnDelete();
            $table->date('expected_registry_date')->nullable()->after('due_date');
            $table->decimal('registry_amount', 12, 2)->nullable()->after('land_size');
            $table->string('payment_words', 255)->nullable()->after('registry_amount');
            $table->string('id_card_no', 60)->nullable()->after('payment_words');
            $table->index('booking_mode', 'registries_booking_mode_idx');
            $table->index('lock_status', 'registries_lock_status_idx');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('receipt_no', 40)->nullable()->after('reference_no');
            $table->string('source_table', 50)->nullable()->after('receipt_no');
            $table->boolean('is_legacy')->default(false)->after('source_table');
            $table->index('receipt_no', 'payments_receipt_no_idx');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_receipt_no_idx');
            $table->dropColumn(['receipt_no', 'source_table', 'is_legacy']);
        });

        Schema::table('registries', function (Blueprint $table) {
            $table->dropIndex('registries_booking_mode_idx');
            $table->dropIndex('registries_lock_status_idx');
            $table->dropConstrainedForeignId('check_by_agent_id');
            $table->dropUnique(['customer_reg_no']);
            $table->dropColumn([
                'customer_reg_no',
                'booking_mode',
                'installment_amount',
                'down_payment',
                'lock_status',
                'nominee_name',
                'expected_registry_date',
                'registry_amount',
                'payment_words',
                'id_card_no',
            ]);
        });

        Schema::table('arazis', function (Blueprint $table) {
            $table->dropIndex('arazis_legacy_code_idx');
            $table->dropColumn(['legacy_arazi_code', 'block', 'plot_type']);
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sponsor_agent_id');
            $table->dropUnique(['form_code']);
            $table->dropColumn(['form_code', 'rank_title', 'legacy_percent']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['legacy_customer_code']);
            $table->dropColumn(['secondary_mobile', 'id_document_no', 'legacy_customer_code']);
        });
    }
};
