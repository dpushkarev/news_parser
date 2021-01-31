<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_sources', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('title', 256);
            $table->string('code', 50);
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('news_source_id');
            $table->string('external_id', 100);
            $table->string('url', 1024);
            $table->string('title', 256);
            $table->text('description')->nullable();
            $table->string('image_url', 1024)->nullable();
            $table->tinyInteger('by_partner')->default(0);
            $table->dateTime('published_time');
            $table->timestamps();

            $table->unique(['news_source_id', 'external_id'], 'idx__unique__news_source_id__external_id');
            $table->foreign('news_source_id')->on('news_sources')->references('id')->onDelete('CASCADE');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_sources');
    }
}
