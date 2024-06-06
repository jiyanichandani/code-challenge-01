<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Bill;
use Faker\Factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Bill::factory(50)->create();

        foreach (Bill::all() as $bill) {
            $bill->users()->attach([rand(1, 10)]);
        };
    }
}
