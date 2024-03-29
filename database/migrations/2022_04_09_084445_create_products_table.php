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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdX('parent_id')
                ->nullable()
                ->constrained($table->getTable())
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdForX(\App\Models\Brand::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('type');
            $table->string('name', 192)->unique();
            $table->string('slug', 192)->unique();
            $table->string('sku', 25)->nullable()->unique();
            $table->string('barcode', 192)->nullable()->unique();

            $table->decimal('purchase_price')->default(0);
            $table->decimal('regular_price')->default(0);
            $table->decimal('discount_amount')->default(0);
            $table->enum('discount_type', ['flat', 'percent']);
            $table->decimal('sale_price')->default(0);

            $table->longText('description')->nullable();
            $table->json('attributes')->nullable();

            $table->boolean('enabled')->default(true);
            $table->boolean('scheduled')->default(false);
            $table->timestamp('sale_start_date')->nullable();
            $table->timestamp('sale_end_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['parent_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
