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
        Schema::create('team_members', function (Blueprint $table) {
            $table->increments('member_id');
            $table->integer('team_id');
            $table->string('full_name_member', 100);
            $table->string('phone_number_member', 15);
            $table->enum('gender_member', ['Male', 'Female', 'Other']);
            $table->timestamps(); // Menambahkan created_at dan updated_at
            $table->timestamp('deleted_at')->nullable(); // Menambahkan deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};