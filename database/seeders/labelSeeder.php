<?php

namespace Database\Seeders;

use App\Models\label;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class labelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        label::factory(10)->create();
    }
}
