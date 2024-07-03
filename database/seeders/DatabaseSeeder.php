<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Scrap;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password'=>bcrypt('password'),
        ]);
        Product::create(
            [
                'name' => 'Aluminium',
                'stock' => 0.000,
                'is_material'=>1,
            ]
        );
        Product::create(
            [
                'name' => 'Copper',
                'stock' => 0.000,
                'is_material'=>1,
            ]
        );
        Product::create(
            [
                'name' => 'Iron',
                'stock' => 0.000,
                'is_material'=>1,
            ]
        );
        Product::create(
            [
                'name' => 'Aluminium Ingot',
                'stock' => 0.000,
                'is_material'=>0,
            ]
        );
        Product::create(
            [
                'name' => 'Kitty',
                'stock' => 0.000,
                'is_material'=>0,
            ]
        );
        Scrap::create(
            [
                'name' => 'TALK',
                'stock' => 0.000,
                'is_base'=>1,
            ]
        );
    }
}
