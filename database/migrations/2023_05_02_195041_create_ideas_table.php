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
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('created_at');
            $table->string('title', 100);
            $table->string('description', 1000);
            $table->unsignedTinyInteger('type'); //0 - not approved; 1 - approved;
            $table->unsignedBigInteger('funds');
            $table->json('tag_ids');
            $table->json('hearts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};
