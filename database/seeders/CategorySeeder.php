<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kebersihan', 'description' => 'Layanan bersih-bersih rumah dan kantor'],
            ['name' => 'Perbaikan Rumah', 'description' => 'Layanan perbaikan dan renovasi'],
            ['name' => 'Elektronik', 'description' => 'Servis dan perbaikan elektronik'],
        ];
        foreach ($categories as $cat) {
            \App\Models\CategoryModel::create($cat);
        }
    }
}
