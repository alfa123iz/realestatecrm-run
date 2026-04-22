<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kisan_bonds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kisan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('arazi_id')->constrained()->cascadeOnDelete();
            $table->string('bond_no', 50)->unique();
            $table->date('bond_date');
            $table->decimal('bond_amount', 12, 2)->default(0);
            $table->string('witness_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['kisan_id', 'bond_date'], 'kisan_bonds_kisan_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kisan_bonds');
    }
};
