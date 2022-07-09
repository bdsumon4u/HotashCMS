<?php

namespace Database\Seeders;

use Hotash\Admin\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::factory()->create([
            'name' => 'aDmiN',
            'email' => 'admin@admin.com',
        ]);
    }
}
