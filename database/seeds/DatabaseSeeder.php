<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            [
                'name' => 'Expresso',
                'price' => 200,
                'is_set' => 0
            ],
            [
                'name' => 'Blueberry Mulffin',
                'price' => 300,
                'is_set' => 0
            ],
            [
                'name' => 'Cafe Latle',
                'price' => 400,
                'is_set' => 0
            ],
            [
                'name' => 'Hazenlnut Lattle',
                'price' => 400,
                'is_set' => 0
            ],
            [
                'name' => 'Cappuccino',
                'price' => 400,
                'is_set' => 0
            ],
            [
                'name' => 'Set 1',
                'price' => 500,
                'is_set' => 1
            ]
        ]);

        DB::table('item_set')->insert([
            [
                'item_id' => 1,
                'set_id' => 6,
            ],
            [
                'item_id' => 2,
                'set_id' => 6,
            ],
        ]);
    }
}
