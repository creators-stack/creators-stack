<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('crawl_based_on_file_extension');
            $table->json('image_extensions');
            $table->json('video_extensions');
            $table->boolean('generate_videos_preview')
                ->default(1);
            $table->boolean('mute_videos_preview')
                ->default(0);
            $table->unsignedInteger('videos_preview_parts_count')
                ->default(5)
                ->nullable();
            $table->unsignedInteger('videos_preview_parts_length')
                ->default(1000)
                ->nullable();
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
        Schema::dropIfExists('settings');
    }
}
