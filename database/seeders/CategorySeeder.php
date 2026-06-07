<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'Upper Body,Bodyweight'],
            ['name' => 'Lower Body,Bodyweight'],
            ['name' => 'Core,Bodyweight'],
            ['name' => 'Upper Body,Isolation'],
            ['name' => 'Full Body,Cardio'],
            ['name' => 'Upper Body,Strength'],
            ['name' => 'Lower Body,Strength'],
            ['name' => 'Lower Body,Isolation'],
            ['name' => 'Core,Cardio'],
            ['name' => 'Full Body,Strength'],
        ]);
    }
}
