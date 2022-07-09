<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tenancy()->model()::create([
            'id' => 'foo',
            'theme' => 'default',
        ])->domains()->create([
            'domain' => 'foo',
        ]);

        tenancy()->model()::create([
            'id' => 'bar',
            'theme' => 'default',
        ])->domains()->create([
            'domain' => 'bar',
        ]);
    }
}
