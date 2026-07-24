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
        Schema::create('conflicts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();

            $table->foreignId('raised_by')->constrained('users');

            $table->foreignId('against_user')->constrained('users');

            $table->enum('raised_by_role', ['client','freelancer']);

            $table->string('title');

            $table->text('reason');

            $table->string('attachment')->nullable();

            $table->enum('status', [
                'open',
                'in_review',
                'resolved',
                'rejected'
            ])->default('open');

            $table->text('admin_response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conflicts');
    }
};
