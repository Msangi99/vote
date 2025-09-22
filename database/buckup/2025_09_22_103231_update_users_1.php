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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->change();
            $table->string('email', 255)->change();
            $table->timestamp('email_verified_at')->nullable()->change();
            $table->string('password', 255)->change();
            $table->string('remember_token', 100)->nullable()->change();
            $table->timestamps();
            $table->engine = 'MyISAM';
            $table->collation('utf8mb4_general_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};