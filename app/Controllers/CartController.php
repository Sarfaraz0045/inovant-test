<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\ProductModel;
use CodeIgniter\RESTful\ResourceController;

class CartController extends ResourceController
{
    protected $cartModel;
    protected $productModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->productModel = new ProductModel();
    }

    // Fetch all cart items
    public function list()
    {
        $cartItems = $this->cartModel->select('cart.id, products.name as product_name, products.price, cart.quantity')
            ->join('products', 'products.id = cart.product_id')
            ->where(['cart.user_id' => 1, 'cart.action' => 0])
            ->findAll();

        return $this->response->setJSON($cartItems);
    }

    // Add product to cart
    public function add()
    {
        $product_id = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity');

        if (!$product_id || !$quantity) {
            return $this->fail('Product ID and quantity are required', 400);
        }

        $this->cartModel->insert([
            'user_id' => 1, // Hardcoded user_id = 1
            'product_id' => $product_id,
            'quantity' => $quantity,
        ]);

        return $this->respondCreated(['message' => 'Product added to cart']);
    }

    public function updateQuantity($id)
    {
        $quantity = $this->request->getPost('quantity');

        if ($quantity <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Quantity must be at least 1']);
        }

        $cartItem = $this->cartModel->find($id);

        if ($cartItem) {
            $this->cartModel->update($id, ['quantity' => $quantity]);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Quantity updated']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Item not found']);
    }



    // Remove item from cart
    public function remove($id)
    {
        $cartItem = $this->cartModel->find($id);

        if ($cartItem) {
            $this->cartModel->update($id, ['action' => 1]); // Soft delete
            return $this->response->setJSON(['status' => 'success', 'message' => 'Item marked as removed']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Item not found']);
    }

    public function product()
    {
        return view('products/index');
    }
}
