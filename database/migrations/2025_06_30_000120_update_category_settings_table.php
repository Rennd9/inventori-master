<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCategorySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_settings', function (Blueprint $table) {
            // Jika masih menggunakan user_id, ubah ke user_type
            if (Schema::hasColumn('category_settings', 'user_id')) {
                $table->dropColumn('user_id');
            }
            
            // Tambah kolom user_type jika belum ada
            if (!Schema::hasColumn('category_settings', 'user_type')) {
                $table->integer('user_type')->after('id')->comment('1=Admin, 2=Chef, 3=User');
            }
            
            // Tambah index untuk performa
            $table->index(['user_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_settings', function (Blueprint $table) {
            $table->dropIndex(['user_type', 'is_active']);
            $table->dropColumn('user_type');
            $table->unsignedBigInteger('user_id')->after('id');
        });
    }
}

/*
ATAU jika Anda ingin membuat tabel baru:

Schema::create('category_settings', function (Blueprint $table) {
    $table->id();
    $table->integer('user_type')->comment('1=Admin, 2=Chef, 3=User');
    $table->unsignedBigInteger('category_id');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
    $table->index(['user_type', 'is_active']);
    $table->unique(['user_type', 'category_id']); // Mencegah duplikasi
});
*/