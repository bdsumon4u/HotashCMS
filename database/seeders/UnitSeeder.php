<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'long_name' => 'Pieces',
            'short_name' => 'Pcs',
        ]);
        Unit::create([
            'long_name' => 'Dollar',
            'short_name' => '$',
        ]);
        Unit::create([
            'long_name' => 'Hali',
            'short_name' => 'Hl',
        ]);
        Unit::create([
            'long_name' => 'Dozens',
            'short_name' => 'Dz',
        ]);
        Unit::create([
            'long_name' => 'Boxes',
            'short_name' => 'Bx',
        ]);
        Unit::create([
            'long_name' => 'Packets',
            'short_name' => 'pac',
        ]);
    }
}
