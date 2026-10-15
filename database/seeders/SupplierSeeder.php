<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Nusantara Digital Supply',
                'phone' => '081200000001',
                'email' => 'nusantara.demo@example.com',
                'address' => 'Jakarta',
            ],
            [
                'name' => 'Cirebon Computer Center',
                'phone' => '081200000002',
                'email' => 'cirebon.demo@example.com',
                'address' => 'Cirebon, Jawa Barat',
            ],
            [
                'name' => 'Sinar Elektronik Mandiri',
                'phone' => '081200000003',
                'email' => 'sinar.demo@example.com',
                'address' => 'Bandung, Jawa Barat',
            ],
            [
                'name' => 'Prima Network Indonesia',
                'phone' => '081200000004',
                'email' => 'prima.demo@example.com',
                'address' => 'Jakarta',
            ],
            [
                'name' => 'DataTech Distributor',
                'phone' => '081200000005',
                'email' => 'datatech.demo@example.com',
                'address' => 'Bandung, Jawa Barat',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['name' => $supplier['name']],
                [
                    'phone' => $supplier['phone'],
                    'email' => $supplier['email'],
                    'address' => $supplier['address'],
                ]
            );
        }
    }
}
