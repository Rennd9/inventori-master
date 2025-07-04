<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_has_expiration_to_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Tambahkan kolom setelah 'name'
            $table->boolean('has_expiration')->default(false)->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('has_expiration');
        });
    }
};