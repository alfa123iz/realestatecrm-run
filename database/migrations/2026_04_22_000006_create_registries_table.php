<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('arazi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained()->cascadeOnDelete();
            $table->date('registry_date');
            $table->decimal('land_size', 10, 2);
            $table->string('witness_name');
            $table->decimal('broker_commission', 5, 2)->default(0);
            $table->decimal('advance_amount', 10, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'partial', 'completed', 'expired'])->default('pending');
            $table->timestamps();

            $table->unique('arazi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registries');
    }
};
