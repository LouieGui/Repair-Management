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
        Schema::create('repairs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('technician_id')->nullable();
            $table->text('reported_issue');
            $table->text('accessories')->nullable();
            $table->enum('status', [
                'received',
                'diagnosing',
                'waiting_approval',
                'approved',
                'waiting_parts',
                'in_progress',
                'testing',
                'ready_for_pickup',
                'completed',
                'cancelled'
            ])->default('received');
            $table->text('diagnosis')->nullable();
            $table->text('technician_notes')->nullable();
            $table->date('date_received')->nullable();
            $table->date('estimated_completion_date')->nullable();
            $table->date('date_completed')->nullable();
            $table->integer('warranty_days')->default(90);
            $table->date('warranty_until_date')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('device_id')->references('id')->on('devices');
            $table->foreign('technician_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
