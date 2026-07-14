<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema->create('order_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            $table->string('type', 30)->index();
            $table->string('name', 255);
            $table->string('file_path', 500)->nullable();
            $table->string('external_url', 500)->nullable();
            $table->unsignedBigInteger('bitrix_file_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema->dropIfExists('order_documents');
    }
};
