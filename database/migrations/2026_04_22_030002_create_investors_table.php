<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile', 20);
            $table->string('address')->nullable();
            $table->decimal('investment_amount', 14, 2)->default(0);
            $table->decimal('return_percentage', 5, 2)->default(0);
            $table->date('invested_on')->nullable();
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamps();

            $table->index(['status', 'invested_on'], 'investors_status_invested_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
