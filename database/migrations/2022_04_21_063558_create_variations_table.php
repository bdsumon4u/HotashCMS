<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdForX(\App\Models\Product::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('type');
            $table->string('name')->index();
            $table->string('sku', 25)->index();
            $table->string('barcode', 192)->nullable();

            $table->decimal('regular_price');
            $table->decimal('discount_amount');
            $table->enum('discount_type', ['flat', 'percent']);
            $table->decimal('sale_price');

            $table->mediumText('note')->nullable();

            $table->boolean('enabled')->default(false);
            $table->boolean('schedule')->default(false);
            $table->timestamp('sale_start_date')->nullable();
            $table->timestamp('sale_end_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['product_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variations');
    }
};
