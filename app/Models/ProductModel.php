<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'price', 'status','created_at', 'updated_at'];

    public function getProductsWithImages()
    {
        $products = $this->db->table('products')
            ->select('products.id, products.name, products.description, products.price, GROUP_CONCAT(product_images.image_path) as images')
            ->join('product_images', 'product_images.product_id = products.id', 'left')
            ->where('status',0)
            ->groupBy('products.id')
            ->get()
            ->getResultArray();

        return $products;
    }
}
