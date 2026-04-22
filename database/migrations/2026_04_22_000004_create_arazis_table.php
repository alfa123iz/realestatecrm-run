<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arazis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kisan_id')->constrained()->cascadeOnDelete();
            $table->string('location');
            $table->string('plot_number');
            $table->decimal('size', 10, 2);
            $table->text('coordinates')->nullable();
            $table->enum('status', ['available', 'sold'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arazis');
    }
};
