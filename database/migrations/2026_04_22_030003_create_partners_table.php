<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile', 20);
            $table->string('address')->nullable();
            $table->decimal('share_percentage', 5, 2)->default(0);
            $table->decimal('capital_amount', 14, 2)->default(0);
            $table->date('joined_on')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['status', 'joined_on'], 'partners_status_joined_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
