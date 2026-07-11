<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('free_lancer_profile_task_category', function (Blueprint $table) {
            $table->id();

            $table->foreignId('free_lancer_profile_id')
                ->constrained('free_lancer_profiles')
                ->cascadeOnDelete();

            $table->foreignId('task_category_id')
                ->constrained('task_categories')
                ->cascadeOnDelete();

            $table->timestamps();
               $table->unique(
                                ['free_lancer_profile_id', 'task_category_id'],
                                'flp_tc_unique'
                            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_lancer_profile_task_category');
    }
};