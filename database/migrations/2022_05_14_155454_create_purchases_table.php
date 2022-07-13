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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignIdForX(\App\Models\Branch::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdForX(\App\Models\Supplier::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->decimal('subtotal');
            $table->decimal('tax')->default(0);
            $table->decimal('discount_amount')->default(0);
            $table->enum('discount_type', ['flat', 'percent']);
            $table->decimal('discount')->default(0);
            $table->decimal('service_charge')->default(0);
            $table->decimal('total');
            $table->text('note')->nullable();
            $table->string('status');
            $table->timestamp('purchased_at');
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
        Schema::dropIfExists('purchases');
    }
};
