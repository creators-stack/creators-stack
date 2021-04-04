<?php

namespace Database\Seeders;

use App\Models\ContentType;
use Illuminate\Database\Seeder;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! ContentType::count()) {
            ContentType::insert([
                [
                    'id' => ContentType::IMAGE,
                    'name' => 'Image',
                ],
                [
                    'id' => ContentType::VIDEO,
                    'name' => 'Video',
                ],
            ]);
        }
    }
}
