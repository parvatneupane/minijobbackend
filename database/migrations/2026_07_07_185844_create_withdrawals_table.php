<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal('amount',10,2);

            $table->string('payment_method')->default('esewa');

            $table->string('account_name');

            $table->string('account_number');

            $table->string('transaction_id')->nullable();

            $table->enum('status',[
                'pending',
                'approved',
                'completed',
                'rejected'
            ])->default('pending');

            $table->text('remarks')->nullable();

            $table->timestamp('requested_at')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};