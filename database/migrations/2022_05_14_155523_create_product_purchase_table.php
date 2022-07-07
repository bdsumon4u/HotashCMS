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
            $table->decimal('price');
            $table->integer('quantity');
            $table->decimal('subtotal');
            $table->decimal('discount_amount');
            $table->enum('discount_type', ['flat', 'percent']);
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
