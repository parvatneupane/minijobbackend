<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('free_lancer_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title')->nullable();
            $table->string('profile_image')->nullable();
            $table->text('bio')->nullable();
            $table->integer('experience_years')->default(0);
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->string('location')->nullable();
            $table->text('skills')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->enum('availability', [
                'available',
                'busy',
                'unavailable'
            ])->default('available');

            $table->enum('status', [
                'pending',
                'active',
                'blocked'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_lancer_profiles');
    }
};