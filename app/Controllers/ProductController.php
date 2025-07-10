<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CartModel;
use App\Models\ProductImageModel;
use CodeIgniter\RESTful\ResourceController; // Use ResourceController for API methods

class ProductController extends ResourceController
{
    protected $productModel;
    protected $productImageModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->productImageModel = new ProductImageModel();
    }

    // Add a new product with images
    public function create()
    {
        // Get form data
        $data = $this->request->getPost();

        // Insert product into the database
        $productId = $this->productModel->insert($data);

        if ($productId) {
            // Handle file uploads
            if ($images = $this->request->getFiles()) {
                foreach ($images['images'] as $image) {
                    if ($image->isValid() && !$image->hasMoved()) {
                        // Define upload path
                        $uploadPath = 'uploads/';
                        $imageName = $image->getRandomName();

                        // Move the file to the upload path
                        $image->move($uploadPath, $imageName);

                        // Save image path to the database
                        $this->productImageModel->insert([
                            'product_id' => $productId,
                            'image_path' => 'uploads/' . $imageName
                        ]);
                    }
                }
            }

            return $this->respondCreated(['message' => 'Product added successfully', 'product_id' => $productId]);
        }

        return $this->fail('Failed to add product');
    }

    // Fetch all products with images
    public function index()
    {
        $products = $this->productModel->getProductsWithImages();

        if ($products) {
            return $this->respond($products);
        }

        return $this->fail('No products found');
    }

    public function remove($id)
    {
        $productItem = $this->productModel->find($id);

        if ($productItem) {
            $this->productModel->update($id, ['status' => 1]); // Soft delete
            return $this->response->setJSON(['status' => 'success', 'message' => 'Item marked as removed']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Item not found']);
    }

    public function updateProduct($id)
    {
        // Get the updated data from the request
        $data = $this->request->getJSON(); // Assuming JSON data is sent

        // Prepare the update data
        $updateData = [];

        if (isset($data->productName)) {
            $updateData['name'] = $data->productName;
        }
        if (isset($data->description)) {
            $updateData['description'] = $data->description;
        }
        if (isset($data->price)) {
            $updateData['price'] = $data->price;
        }

        // Update the product
        if ($this->productModel->update($id, $updateData)) {
            return $this->respond(['message' => 'Product updated successfully']);
        }

        return $this->fail('Failed to update product');
    }

    public function cart()
    {
        return view('cart/index');
    }

    public function addToCart()
    {
        $json = $this->request->getJSON();

        
        // log_message('debug', 'Received data: ' . print_r($json, true));

        if (!$json) {
            return $this->failValidationErrors('Invalid JSON data.');
        }

        $userId = $json->user_id ?? null;
        $productId = $json->product_id ?? null;
        $quantity = $json->quantity ?? null;

        if (empty($userId) || empty($productId) || empty($quantity)) {
            return $this->failValidationErrors('User ID, Product ID, and Quantity are required.');
        }

        $cartModel = new CartModel();

        $data = [
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ];

        if ($cartModel->insert($data)) {
            return $this->respondCreated(['message' => 'Product added to cart successfully.']);
        } else {
            log_message('error', 'Database error: ' . print_r($cartModel->errors(), true));
            return $this->failServerError('Failed to add product to cart.');
        }
    }
}
