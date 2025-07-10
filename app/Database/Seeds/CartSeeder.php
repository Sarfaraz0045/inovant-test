<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id'    => 1,
                'product_id' => 1, // Make sure this exists in `products`
                'quantity'   => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 1,
                'product_id' => 2, // Make sure this exists in `products`
                'quantity'   => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('cart')->insertBatch($data);
    }
}
