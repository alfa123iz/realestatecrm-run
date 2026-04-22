<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_bond_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('entry_no', 50)->unique();
            $table->date('entry_date');
            $table->enum('entry_type', ['advance', 'installment', 'final', 'penalty', 'other'])->default('installment');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 40)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['registry_id', 'entry_date'], 'cust_bond_pay_registry_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_bond_payments');
    }
};
