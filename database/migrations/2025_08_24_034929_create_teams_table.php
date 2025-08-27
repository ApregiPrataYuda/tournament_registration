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
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('team_id'); // team_id INT AUTO_INCREMENT
            $table->string('team_name', 100); // team_name VARCHAR(100)
            $table->unsignedBigInteger('captain_id'); // captain_id BIGINT
            $table->enum('captain_gender', ['Male', 'Female', 'Other']);
            $table->timestamps(); // created_at dan updated_at
            $table->timestamp('deleted_at')->nullable(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};