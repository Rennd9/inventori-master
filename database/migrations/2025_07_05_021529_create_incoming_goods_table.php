<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('incoming_goods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('incoming_goods');
    }
};
