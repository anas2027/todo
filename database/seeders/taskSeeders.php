<?php

namespace Database\Seeders;

use App\Models\task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class taskSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        task::factory(10)->create();
        //
    }
}
