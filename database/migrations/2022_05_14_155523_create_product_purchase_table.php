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
        Schema::create('product_purchase', function (Blueprint $table) {
            $table->id();
            $table->foreignIdForX(\App\Models\Product::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdForX(\App\Models\Purchase::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            ### --- PIVOT COLUMNS --- ###
            $table->foreignIdForX(\App\Models\Unit::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->decimal('price');
            $table->integer('quantity')->default(1);
            $table->decimal('discount_amount')->default(0);
            $table->enum('discount_type', ['flat', 'percent']);
            $table->decimal('tax_amount')->default(0);
            $table->enum('tax_type', ['exclusive', 'inclusive']);
            $table->decimal('net_price');
            $table->decimal('discount')->default(0);
            $table->decimal('tax')->default(0);
            $table->decimal('total');
            ### --- PIVOT COLUMNS --- ###
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_purchase');
    }
};
