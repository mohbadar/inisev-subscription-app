<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Website;

class WebsitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate table
        Website::truncate();

        $faker = \Faker\Factory::create();

        for($i=0; $i< 10; $i++){
            Website::create([
                'name' => $faker->sentence,
                'url' => $faker->url,
                'description' => $faker->paragraph,
            ]);
        }
    }
}
