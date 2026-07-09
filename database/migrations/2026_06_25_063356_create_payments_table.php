<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('contract_id')
                ->constrained('contracts')
                ->cascadeOnDelete();

            $table->foreignId('client_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('freelancer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal('amount',10,2);

            // Platform commission
            $table->decimal('platform_fee',10,2)->default(0);

            // Amount freelancer receives
            $table->decimal('freelancer_amount',10,2)->default(0);

            $table->string('payment_method')->default('esewa');

            $table->longtext('transaction_id')->nullable();

            $table->enum('status',[
                'pending',
                'escrow',
                'released',
                'refunded',
                'failed'
            ])->default('pending');

            $table->timestamp('paid_at')->nullable();

            $table->timestamp('released_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};