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
            $table->string('barcode', 192);
            $table->string('barcode_type', 10);
            $table->string('name', 192)->unique();
            $table->string('slug', 192)->unique();
            $table->float('price', 10, 0);
            $table->foreignIdFor(\App\Models\Brand::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->float('net_tax', 10, 0)->nullable()->default(0);
            $table->string('tax_method', 192)->nullable()->default('1');
            $table->mediumText('note')->nullable();
            $table->float('stock_alert', 10, 0)->nullable()->default(0);
            $table->boolean('is_variant')->default(0);
            $table->boolean('is_active')->default(true);
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
