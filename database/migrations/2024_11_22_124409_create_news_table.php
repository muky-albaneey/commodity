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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->enum('tag', ['Market Updates', 'Analysis', 'Company News', 'Industry Trends']);
            $table->string('title');
            $table->text('description');
            $table->string('image');
            $table->string('link');
            $table->string('author');
            $table->integer('readTime');
            $table->date('date');
            $table->timestamps();
            $table->string('createdBy')->nullable();
            $table->string('updatedBy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
