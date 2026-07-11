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
                ->constrained()
                ->cascadeOnDelete();

            // Professional info
            $table->string('title')->nullable();
            
            $table->text('bio')->nullable();

            $table->integer('experience_years')
                ->default(0);

            $table->decimal('hourly_rate', 10, 2)
                ->default(0);

            $table->text('skills')->nullable();

            // Location
            $table->string('location')->nullable();

            // Availability
            $table->enum('availability', [
                'available',
                'busy',
                'unavailable'
            ])->default('available');

            // Portfolio
            $table->string('portfolio_url')
                ->nullable();

            // Statistics
            $table->decimal('rating', 3, 2)
                ->default(0.00);

            $table->integer('completed_jobs')
                ->default(0);

            
            // Change this:
                $table->decimal('earned_money', 15, 2)->default(0.00);
            

            // Profile status
            $table->enum('status', [
                'inactive',
                'active',
                'blocked'
            ])->default('active');

            $table->timestamps();

            // One profile per freelancer
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_lancer_profiles');
    }
};