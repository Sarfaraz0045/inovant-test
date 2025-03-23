<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management CMS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    /* Custom Styles */
    .card {
        margin-bottom: 20px;
    }

    #productList .card {
        margin-bottom: 10px;
    }

    #productList img {
        max-width: 100%;
        height: auto;
    }
</style>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Product Management CMS</h1>

        <!-- Add Product Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Add Product</h5>
            </div>
            <div class="card-body">
                <form id="addProductForm">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Product Price</label>
                        <input type="number" class="form-control" id="productPrice" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="productImages" class="form-label">Product Images</label>
                        <input type="file" class="form-control" id="productImages" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>

        <!-- Product Listing -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Product List</h5>
            </div>
            <div class="card-body">
                <div id="productList" class="row"></div>
            </div>
        </div>

        <!-- Cart -->
        <div class="card">
            <div class="card-header">
                <h5>Cart</h5>
            </div>
            <div class="card-body">
                <div id="cartList"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Base URL for API
        const API_BASE_URL = 'http://localhost:8080'; // Update with your backend URL

        // Add Product Form Submission
        document.getElementById('addProductForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData();
            formData.append('name', document.getElementById('productName').value);
            formData.append('price', document.getElementById('productPrice').value);

            const files = document.getElementById('productImages').files;
            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }

            try {
                const response = await fetch(`${API_BASE_URL}/add-product`, {
                    method: 'POST',
                    body: formData,
                });
                const result = await response.json();
                alert(result.message);
                fetchProducts(); // Refresh product list
            } catch (error) {
                console.error('Error adding product:', error);
            }
        });

        // Fetch and Display Products
        async function fetchProducts() {
            try {
                const response = await fetch(`${API_BASE_URL}/products`);
                const products = await response.json();

                const productList = document.getElementById('productList');
                productList.innerHTML = '';

                products.forEach(product => {
                    const productCard = `
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text">$${product.price}</p>
                            <div class="mb-3">
                                ${product.images.map(image => `<img src="${API_BASE_URL}/uploads/${image.image_url}" class="img-fluid mb-2">`).join('')}
                            </div>
                            <button onclick="addToCart(${product.id})" class="btn btn-success">Add to Cart</button>
                        </div>
                    </div>
                </div>
            `;
                    productList.innerHTML += productCard;
                });
            } catch (error) {
                console.error('Error fetching products:', error);
            }
        }

        // Add Product to Cart
        async function addToCart(productId) {
            try {
                const response = await fetch(`${API_BASE_URL}/add-to-cart`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    }), // Hardcoded quantity
                });
                const result = await response.json();
                alert(result.message);
                fetchCart(); // Refresh cart list
            } catch (error) {
                console.error('Error adding to cart:', error);
            }
        }

        // Fetch and Display Cart
        async function fetchCart() {
            try {
                const response = await fetch(`${API_BASE_URL}/cart`);
                const cartItems = await response.json();

                const cartList = document.getElementById('cartList');
                cartList.innerHTML = '';

                cartItems.forEach(item => {
                    const cartItem = `
                <div class="card mb-2">
                    <div class="card-body">
                        <h5 class="card-title">Product ID: ${item.product_id}</h5>
                        <p class="card-text">Quantity: ${item.quantity}</p>
                    </div>
                </div>
            `;
                    cartList.innerHTML += cartItem;
                });
            } catch (error) {
                console.error('Error fetching cart:', error);
            }
        }

        // Initial Load
        fetchProducts();
        fetchCart();
    </script>

</body>

</html>