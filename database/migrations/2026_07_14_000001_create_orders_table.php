<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema->create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('bitrix_id')->nullable()->unique();
            $table->string('number', 50)->index();

            $table->string('request_status', 30)->default('new')->index();
            $table->string('order_status', 30)->default('open')->index();

            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('counterparty_name')->nullable();
            $table->string('counterparty_partner')->nullable();
            $table->decimal('amount', 15, 2)->default(0);

            $table->string('payment_method', 50)->nullable();
            $table->string('delivery_method', 50)->nullable();
            $table->text('delivery_details')->nullable();
            $table->decimal('delivery_cost', 15, 2)->nullable();
            $table->boolean('delivery_became_paid')->default(false);

            $table->string('channel', 30)->nullable()->index();

            $table->string('sales_manager_name', 255)->nullable();
            $table->string('sales_manager_email', 255)->nullable();
            $table->string('sales_manager_phone', 50)->nullable();

            $table->string('client_phone', 50)->nullable();
            $table->string('client_email', 255)->nullable();
            $table->string('client_inn', 20)->nullable();
            $table->string('client_company', 255)->nullable();

            $table->timestamp('processing_at')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('reserve_date')->nullable();

            $table->boolean('debt_control_disabled')->default(false);

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index(['request_status', 'manager_id']);
            $table->index(['order_status', 'shipped_at']);
        });
    }

    public function down(): void
    {
        Schema->dropIfExists('orders');
    }
};
