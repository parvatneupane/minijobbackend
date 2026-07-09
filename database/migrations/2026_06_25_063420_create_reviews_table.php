<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('reviews', function (Blueprint $table) {
    $table->id();

    $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();

    $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete();

    $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();

    $table->foreignId('freelancer_id')->constrained('users')->cascadeOnDelete();

    $table->integer('rating');

    $table->text('review')->nullable();

    $table->boolean('recommended')->default(false);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
