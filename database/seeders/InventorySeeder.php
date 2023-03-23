<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $data = [
            [
                'item_id' => 4,
                'qty' => 10,
            ],
        ];

        \App\Models\Inventory::insertOrIgnore($data);

    }
}
