<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateContentTypesTable extends Migration
{
    private const IMAGE_ID = 1;
    private const VIDEO_ID = 2;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('content_types')->insert( [
            [
                'id' => self::IMAGE_ID,
                'name' => 'Image',
            ],
            [
                'id' => self::VIDEO_ID,
                'name' => 'Video',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_types');
    }
}
