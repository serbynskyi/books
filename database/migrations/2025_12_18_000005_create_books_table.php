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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->text('description');
            $table->integer('edition')->nullable();
            $table->foreignId('publisher_id')->constrained()->restrictOnDelete();
            $table->date('published_at')->index();
            $table->string('format');
            $table->integer('pages');
            $table->char('country_code', 2);
            $table->foreign('country_code')->references('code')->on('countries')->restrictOnDelete();
            $table->string('isbn')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
