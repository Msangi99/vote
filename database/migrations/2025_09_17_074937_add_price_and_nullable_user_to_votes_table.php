<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->integer('price')->nullable()->after('player_id');
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn('price');
            // Reverting nullable user_id is more complex, often left as is or handled manually if strict non-nullable is required.
            // For simplicity, we'll leave it as nullable on rollback, or assume it's handled by a previous migration.
            // $table->foreignId('user_id')->change(); // This would require knowing the original state
        });
    }
};
