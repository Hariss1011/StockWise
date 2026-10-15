<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['SKU0001', 'Laptop Office 14 inci i3', 'Laptop & Komputer', 'Nusantara Digital Supply', 'unit', 5200000, 5850000, 4, 2, 'Laptop untuk pekerjaan kantor.'],
            ['SKU0002', 'Laptop Office 15 inci i5', 'Laptop & Komputer', 'Nusantara Digital Supply', 'unit', 7600000, 8350000, 3, 1, 'Laptop untuk pekerjaan dan kuliah.'],
            ['SKU0003', 'Mini PC Office', 'Laptop & Komputer', 'Cirebon Computer Center', 'unit', 3850000, 4350000, 2, 1, 'Mini PC untuk kantor atau kasir.'],

            ['SKU0004', 'Keyboard USB Standard', 'Periferal', 'Cirebon Computer Center', 'unit', 65000, 95000, 18, 5, 'Keyboard kabel untuk penggunaan harian.'],
            ['SKU0005', 'Keyboard Mechanical 87 Key', 'Periferal', 'Nusantara Digital Supply', 'unit', 280000, 345000, 7, 2, 'Keyboard mechanical compact.'],
            ['SKU0006', 'Mouse USB Optical', 'Periferal', 'Cirebon Computer Center', 'unit', 35000, 55000, 25, 8, 'Mouse kabel optik.'],
            ['SKU0007', 'Mouse Wireless 2.4 GHz', 'Periferal', 'Cirebon Computer Center', 'unit', 85000, 125000, 12, 4, 'Mouse nirkabel dengan receiver USB.'],
            ['SKU0008', 'Webcam HD 720p', 'Periferal', 'Sinar Elektronik Mandiri', 'unit', 145000, 195000, 6, 2, 'Webcam untuk rapat daring.'],
            ['SKU0009', 'Headset USB', 'Periferal', 'Sinar Elektronik Mandiri', 'unit', 120000, 165000, 8, 3, 'Headset dengan mikrofon.'],
            ['SKU0010', 'Monitor LED 22 inci', 'Periferal', 'Nusantara Digital Supply', 'unit', 1050000, 1250000, 5, 2, 'Monitor Full HD.'],
            ['SKU0011', 'Monitor LED 24 inci IPS', 'Periferal', 'Nusantara Digital Supply', 'unit', 1450000, 1690000, 3, 1, 'Monitor IPS Full HD.'],

            ['SKU0012', 'Kabel HDMI 1.5 meter', 'Aksesori Komputer', 'Cirebon Computer Center', 'pcs', 18000, 30000, 30, 10, 'Kabel HDMI untuk monitor dan TV.'],
            ['SKU0013', 'Kabel HDMI 3 meter', 'Aksesori Komputer', 'Cirebon Computer Center', 'pcs', 28000, 45000, 14, 5, 'Kabel HDMI sepanjang tiga meter.'],
            ['SKU0014', 'USB Hub 4 Port', 'Aksesori Komputer', 'Sinar Elektronik Mandiri', 'unit', 45000, 70000, 10, 3, 'USB hub dengan empat port.'],

            ['SKU0015', 'Kabel LAN Cat6 1 meter', 'Jaringan', 'Prima Network Indonesia', 'pcs', 9000, 18000, 35, 10, 'Kabel patch jaringan Cat6.'],
            ['SKU0016', 'Kabel LAN Cat6 5 meter', 'Jaringan', 'Prima Network Indonesia', 'pcs', 28000, 45000, 16, 5, 'Kabel jaringan Cat6 lima meter.'],
            ['SKU0017', 'Router Wi-Fi AC1200', 'Jaringan', 'Prima Network Indonesia', 'unit', 310000, 385000, 4, 2, 'Router dual-band untuk rumah dan usaha.'],
            ['SKU0018', 'USB Wi-Fi Adapter', 'Jaringan', 'Prima Network Indonesia', 'unit', 95000, 135000, 7, 2, 'Adaptor Wi-Fi melalui USB.'],

            ['SKU0019', 'Flashdisk USB 32 GB', 'Penyimpanan Data', 'DataTech Distributor', 'pcs', 42000, 60000, 22, 8, 'Flashdisk kapasitas 32 GB.'],
            ['SKU0020', 'Flashdisk USB 64 GB', 'Penyimpanan Data', 'DataTech Distributor', 'pcs', 65000, 90000, 15, 5, 'Flashdisk kapasitas 64 GB.'],
            ['SKU0021', 'SSD SATA 2.5 inci 480 GB', 'Penyimpanan Data', 'DataTech Distributor', 'unit', 420000, 495000, 5, 2, 'SSD untuk upgrade laptop atau PC.'],
            ['SKU0022', 'SSD NVMe 1 TB', 'Penyimpanan Data', 'DataTech Distributor', 'unit', 890000, 1025000, 3, 1, 'SSD NVMe untuk perangkat kompatibel.'],
            ['SKU0023', 'HDD External 1 TB', 'Penyimpanan Data', 'DataTech Distributor', 'unit', 720000, 825000, 2, 1, 'Hard disk eksternal untuk backup.'],

            ['SKU0024', 'RAM DDR4 8 GB', 'Komponen PC', 'Nusantara Digital Supply', 'unit', 215000, 265000, 6, 2, 'Modul RAM DDR4 8 GB.'],
            ['SKU0025', 'RAM DDR4 16 GB', 'Komponen PC', 'Nusantara Digital Supply', 'unit', 365000, 435000, 4, 2, 'Modul RAM DDR4 16 GB.'],
            ['SKU0026', 'Power Supply 450W', 'Komponen PC', 'Cirebon Computer Center', 'unit', 320000, 395000, 3, 1, 'PSU untuk rakitan PC entry-level.'],
            ['SKU0027', 'Casing Micro ATX', 'Komponen PC', 'Cirebon Computer Center', 'unit', 285000, 350000, 2, 1, 'Casing komputer format Micro ATX.'],

            ['SKU0028', 'Speaker USB Mini', 'Elektronik', 'Sinar Elektronik Mandiri', 'unit', 75000, 110000, 9, 3, 'Speaker ringkas dengan daya USB.'],
            ['SKU0029', 'Adaptor USB-C 65W', 'Elektronik', 'Sinar Elektronik Mandiri', 'unit', 185000, 245000, 5, 2, 'Adaptor daya USB-C 65W.'],
            ['SKU0030', 'UPS 650 VA', 'Elektronik', 'Nusantara Digital Supply', 'unit', 520000, 625000, 3, 1, 'UPS untuk membantu perlindungan daya.'],
        ];

        foreach ($products as $row) {
            [
                $sku,
                $name,
                $categoryName,
                $supplierName,
                $unit,
                $purchasePrice,
                $sellingPrice,
                $initialStock,
                $minimumStock,
                $description,
            ] = $row;

            $category = Category::where('name', $categoryName)->firstOrFail();
            $supplier = Supplier::where('name', $supplierName)->firstOrFail();

            Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'category_id' => $category->id,
                    'supplier_id' => $supplier->id,
                    'name' => $name,
                    'description' => $description,
                    'unit' => $unit,
                    'purchase_price' => $purchasePrice,
                    'selling_price' => $sellingPrice,
                    'initial_stock' => $initialStock,
                    'minimum_stock' => $minimumStock,
                ]
            );
        }
    }
}
