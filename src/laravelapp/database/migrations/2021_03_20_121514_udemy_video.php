<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UdemyVideo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('udemy_video', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->default(null);
            $table->string('udemy_url')->nullable()->default(null);
            $table->string('udemy_image_url')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('udemy_video');
    }
}
