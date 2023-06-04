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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cover')->nullable();
            $table->string('price')->nullable();
            $table->string('file')->nullable();
            $table->bigInteger('type_id')->unsigned();
            $table->bigInteger('author_id')->unsigned();
            $table->bigInteger('editor_id')->unsigned();
            $table->bigInteger('publisher_id')->unsigned();
            $table->bigInteger('status_id')->unsigned();

            $table->timestamp('published_date')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
