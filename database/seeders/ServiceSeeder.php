<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['category_id' => 1, 'name' => 'Cuci Lantai', 'base_price' => 50000, 'unit' => 'visit'],
            ['category_id' => 1, 'name' => 'Cuci Jendela', 'base_price' => 75000, 'unit' => 'visit'],
            ['category_id' => 2, 'name' => 'Pasang AC', 'base_price' => 200000, 'unit' => 'unit'],
            ['category_id' => 2, 'name' => 'Perbaikan Pipa', 'base_price' => 150000, 'unit' => 'visit'],
            ['category_id' => 3, 'name' => 'Servis HP', 'base_price' => 100000, 'unit' => 'unit'],
        ];
        foreach ($services as $s) {
            \App\Models\ServiceModel::create($s);
        }
    }
}
