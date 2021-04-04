<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creator_id')
                ->references('id')
                ->on('creators')
                ->onDelete('cascade');

            $table->foreignId('content_type_id')
                ->references('id')
                ->on('content_types');

            $table->string('path')
                ->unique();

            $table->string('filename')
                ->index();

            $table->string('hash')
                ->unique();

            $table->string('thumbnail')
                ->unique()
                ->nullable();

            $table->string('preview')
                ->unique()
                ->nullable();

            $table->unsignedBigInteger('size')
                ->index();

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
        Schema::dropIfExists('files');
    }
}
