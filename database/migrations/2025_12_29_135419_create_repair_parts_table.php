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
        Schema::create('repair_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('repair_id');
            $table->unsignedBigInteger('part_id')->nullable();
            $table->string('custom_part_name', 255)->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->boolean('is_approved')->default(false);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('repair_id')->references('id')->on('repairs');
            $table->foreign('part_id')->references('id')->on('parts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_parts');
    }
};
