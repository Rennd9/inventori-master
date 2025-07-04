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
        Schema::table('restock_requests', function (Blueprint $table) {
            $table->dropColumn('requested_quantity');
        });
    }

    public function down()
    {
        Schema::table('restock_requests', function (Blueprint $table) {
            $table->integer('requested_quantity')->nullable();
        });
    }

};
