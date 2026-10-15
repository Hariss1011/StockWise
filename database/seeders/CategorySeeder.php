<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laptop & Komputer',
                'description' => 'Laptop, mini PC, dan perangkat komputer.',
            ],
            [
                'name' => 'Aksesori Komputer',
                'description' => 'Kabel, USB hub, dan aksesori pendukung.',
            ],
            [
                'name' => 'Periferal',
                'description' => 'Keyboard, mouse, monitor, webcam, dan headset.',
            ],
            [
                'name' => 'Jaringan',
                'description' => 'Router, adaptor Wi-Fi, dan kabel jaringan.',
            ],
            [
                'name' => 'Penyimpanan Data',
                'description' => 'Flashdisk, SSD, dan hard disk.',
            ],
            [
                'name' => 'Komponen PC',
                'description' => 'RAM, power supply, dan komponen komputer.',
            ],
            [
                'name' => 'Elektronik',
                'description' => 'Adaptor daya, speaker, UPS, dan perangkat elektronik.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
