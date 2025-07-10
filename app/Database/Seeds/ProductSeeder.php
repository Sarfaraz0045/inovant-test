<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Product A',
                'description' => 'Description for Product A',
                'price' => 19.99,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Product B',
                'description' => 'Description for Product B',
                'price' => 29.99,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('products')->insertBatch($data);
    }
}
