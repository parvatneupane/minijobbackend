<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('verifications', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('full_name');

    $table->string('citizenship_front');
    $table->string('citizenship_back');

    $table->string('pan_card')->nullable();

    $table->enum('status', [
        'pending',
        'approved',
        'rejected'
    ])->default('pending');

    $table->text('remarks')->nullable();

    $table->timestamp('verified_at')->nullable();

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('client_profiles');
    }
};