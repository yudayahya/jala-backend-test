<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@jala.tech',
            'password' => bcrypt('admin'),
            'created_at' => date(now()),
            'updated_at' => date(now()),
        ]);

        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'User']);
        $user = User::first();
        $user->assignRole('Super Admin');

        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            DB::table('products')->insert([
                'sku' => 'BRG' . $faker->ean8(),
                'name' => 'Ini Nama Produk ' . $index,
                'price' => $faker->numberBetween(5000, 30000),
                'created_at' => date(now()),
                'updated_at' => date(now()),
            ]);
        }
    }
}
