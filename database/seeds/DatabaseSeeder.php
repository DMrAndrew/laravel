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

        App\Ingredient::truncate();
        \App\Medicament::truncate();
        App\pivot::truncate();
        $ings = factory(App\Ingredient::class, 100)->create();
        $meds = factory(App\Medicament::class, 500)->create();

        foreach ($meds as $med) {
            $med->ingredients()->attach($ings->random(5));
            $med->count = $med->ingredients()->get()->count();
            $med->save();
        }
        foreach ($ings as $item) {
            $item->checkStock();
        }


        // $this->call(UserSeeder::class);
    }
}
