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
    Schema::create('market_officers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('nik')->unique();
        $table->string('phone');
        $table->string('image_url')->nullable();
        $table->string('username')->unique();
        $table->string('password');
        $table->unsignedBigInteger('market_id');
        $table->timestamps();
        $table->boolean('is_active')->default(true);

    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_officers');
    }
};
