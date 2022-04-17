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
            $table->string('name', 192)->unique();
            $table->string('slug', 192)->unique();
            $table->string('sku', 25)->unique();
            $table->string('barcode', 192)->nullable();
            $table->integer('regular_price');
            $table->integer('sale_price');
            $table->boolean('schedule');
            $table->foreignIdFor(\App\Models\Brand::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->float('net_tax', 10, 0)->nullable()->default(0);
            $table->string('tax_method', 192)->nullable()->default('1');
            $table->mediumText('note')->nullable();
            $table->float('stock_alert', 10, 0)->nullable()->default(0);
            $table->json('attributes')->nullable();
            $table->boolean('has_variation')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('sale_start_date')->nullable();
            $table->timestamp('sale_end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
