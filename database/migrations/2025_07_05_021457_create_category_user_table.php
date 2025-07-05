<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryUserTable extends Migration
{
    public function up(): void
    {
        Schema::create('category_user', function (Blueprint $table) {
            $table->id();
            
            // Pastikan foreignId dan bertipe unsignedBigInteger
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_user');
    }
}

