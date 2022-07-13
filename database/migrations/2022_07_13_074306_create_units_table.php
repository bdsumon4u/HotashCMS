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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignIdX('parent_id')
                ->nullable()
                ->constrained($table->getTable())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('long_name', 25)->unique();
            $table->string('short_name', 10)->unique();
            $table->enum('operator', ['*', '/'])->default('*');
            $table->integer('operand')->default(1);
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
        Schema::dropIfExists('units');
    }
};
