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
        Schema::create('returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('repair_id');
            $table->unsignedBigInteger('new_repair_id')->nullable();
            $table->enum('return_type', ['warranty_claim', 'recheck', 'different_issue'])->default('recheck');
            $table->date('return_date')->nullable();
            $table->text('return_reason');
            $table->boolean('is_same_issue')->default(true);
            $table->boolean('is_under_warranty')->default(false);
            $table->unsignedBigInteger('received_by')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('repair_id')->references('id')->on('repairs');
            $table->foreign('new_repair_id')->references('id')->on('repairs');
            $table->foreign('received_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
