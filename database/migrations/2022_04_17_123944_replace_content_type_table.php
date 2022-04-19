<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const IMAGE_ID = 1;
    private const VIDEO_ID = 2;
    private const IMAGE = 'Image';
    private const VIDEO = 'Video';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('content_type')
                ->after('creator_id')
                ->nullable();
        });

        DB::table('files')
            ->where('content_type_id', self::IMAGE_ID)
            ->update([
                'content_type' => self::IMAGE,
            ]);

        DB::table('files')
            ->where('content_type_id', self::VIDEO_ID)
            ->update([
                'content_type' => self::VIDEO,
            ]);

        Schema::table('files', function (Blueprint $table) {
            $table->string('content_type')->nullable(false)->change();

            $table->dropForeign(['content_type_id']);
            $table->dropColumn('content_type_id');
        });

        Schema::drop('content_types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('content_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('content_types')->insert([
            [
                'id' => self::IMAGE_ID,
                'name' => self::IMAGE,
            ],
            [
                'id' => self::VIDEO_ID,
                'name' => self::VIDEO,
            ],
        ]);

        Schema::table('files', function (Blueprint $table) {
            $table->foreignId('content_type_id')
                ->after('creator_id')
                ->nullable()
                ->constrained('content_types');
        });

        DB::table('files')
            ->where('content_type', self::IMAGE)
            ->update([
                'content_type_id' => self::IMAGE_ID,
            ]);

        DB::table('files')
            ->where('content_type', self::VIDEO)
            ->update([
                'content_type_id' => self::VIDEO_ID,
            ]);

        Schema::table('files', function (Blueprint $table) {
            $table->foreignId('content_type_id')->nullable(false)->change();

            $table->dropColumn('content_type');
        });
    }
};
