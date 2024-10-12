<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InitializeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            Initialize\RoleSeeder::class,
            Initialize\ProviderSeeder::class,
        ]);
    }
}
