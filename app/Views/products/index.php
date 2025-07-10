<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</head>
<style>
    #link-cart {
        float: right;
        width: 70px;
        height: 40px;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        width: 50%;
        position: relative;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 28px;
        cursor: pointer;
    }

    /* Modal container */
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
    }

    /* Modal content (image) */
    .custom-modal-content {
        margin: auto;
        display: block;
        max-width: 80%;
        max-height: 80%;
        border-radius: 6px;
    }

    /* Close button */
    .custom-close {
        position: absolute;
        top: 30px;
        right: 45px;
        color: #fff;
        font-size: 55px;
        font-weight: bold;
        cursor: pointer;
    }
</style>

<body>
    <div class="container mt-5">
        <button id="addProductBtn" class="btn btn-primary">Add Product</button>

        <button id="link-cart" class="btn btn-primary d-flex align-items-center justify-content-center" onclick="window.location.href='<?= base_url('cart') ?>'">
            <i style="font-size: 25px;" class="bi bi-cart"></i>
        </button>
        <div class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 class="mb-4">Add Product</h2>

                <!-- Add Product Form -->
                <form id="productForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" class="form-control" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Images</label>
                        <input type="file" class="form-control" name="images[]" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>

        <!-- Custom Image Modal -->
        <div id="customImageModal" class="custom-modal">
            <span class="custom-close" onclick="closeCustomImageModal()">&times;</span>
            <img class="custom-modal-content" id="customModalImg">
        </div>


        <hr>

        <!-- Product List -->
        <h3>Product List</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr style="text-align: center;">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Images</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="productList">
                <!-- Products will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $(".modal").hide();
            $("#addProductBtn").click(function() {
                $(".modal").fadeIn();
            });
            $(".close, .modal").click(function(e) {
                if ($(e.target).hasClass("modal") || $(e.target).hasClass("close")) {
                    $(".modal").fadeOut();
                }
            });

            $(".modal-content").click(function(e) {
                e.stopPropagation();
            });
        });
    </script>

    <script>
        var base_url = "<?php echo base_url(); ?>";

        $(document).ready(function() {
            // Fetch products on page load
            fetchProducts();
        });

        // Handle form submission
        $('#productForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: base_url + '/products',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert('Product Added Successfully');
                    $('#productForm')[0].reset();
                    fetchProducts();
                },
                error: function(xhr, status, error) {
                    console.error("Error adding product:", xhr.responseText);
                }
            });
        });

        // Fetch products function
        function fetchProducts_old() {
            $.ajax({
                url: base_url + '/products',
                type: 'GET',
                success: function(response) {
                    // console.log(response); // Log the response for debugging
                    if (!response || !response.length) {
                        console.error("No products found or invalid response:", response);
                        return;
                    }

                    let rows = '';
                    response.forEach(product => {
                        // Split the images string into an array
                        let imagesArray = product.images ? product.images.split(',') : [];

                        // Generate HTML for images
                        let imagesHtml = imagesArray.length ?
                            imagesArray.map(img => `<img src="${base_url + '/' + img}" width="50" class="me-2">`).join('') :
                            'No Image';

                        // Add a row for the product
                        rows += `<tr>
                            <td>${product.id}</td>
                            <td>${product.name}</td>
                            <td>${product.description}</td>
                            <td>${product.price}</td>
                            <td>${imagesHtml}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" id="editBtn_${product.id}" onclick="enableEdit(${product.id})">Edit</button>
                                <button class="btn btn-success btn-sm d-none" id="saveBtn_${product.id}" onclick="saveQuantity(${product.id})">Save</button>

                                <button class="btn btn-danger btn-sm" onclick="removeProduct(${product.id})">Remove</button>
                            </td>
                        </tr>`;
                    });

                    // Update the table body with the generated rows
                    $('#productList').html(rows);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching products:", xhr.responseText);
                }
            });
        }

        function fetchProducts() {
            $.ajax({
                url: base_url + '/products',
                type: 'GET',
                success: function(response) {

                    if (!response || !response.length) {
                        console.error("No products found or invalid response:", response);
                        return;
                    }

                    let rows = '';
                    response.forEach(product => {

                        let imagesArray = product.images ? product.images.split(',') : [];

                        // let imagesHtml = imagesArray.length ?
                        //     imagesArray.map(img => `<img src="${base_url + '/' + img}" width="55" class="me-2">`).join('') :
                        //     'No Image';

                        let imagesHtml = imagesArray.length ?
                            imagesArray.map(img =>
                                `<img src="${base_url + '/' + img}" width="55" class="me-2" style="cursor: pointer;" onclick="openCustomImageModal('${base_url + '/' + img}')">`
                            ).join('') :
                            'No Image';

                        rows += `<tr id="productRow_${product.id}">
                            <td style="text-align: center;">${product.id}</td>
                            <td>
                                <span id="productName_${product.id}">${product.name}</span>
                                <input type="text" id="productNameInput_${product.id}" value="${product.name}" class="form-control d-none">
                            </td>
                            <td style="width:39%;">
                                <span id="descriptionText_${product.id}">${product.description}</span>
                                <input type="text" id="descriptionInput_${product.id}" value="${product.description}" class="form-control d-none">
                            </td>
                            <td style="width:10%; text-align: center;">
                                <span id="priceText_${product.id}">${product.price}</span>
                                <input type="number" id="priceInput_${product.id}" value="${product.price}" class="form-control d-none">
                            </td>
                            <td style="width:15%; text-align: center;">${imagesHtml}</td>
                            <td style="width:21%; text-align: center;">
                                <button class="btn btn-info btn-sm" onclick="addToCart(${product.id})">Add to Cart</button>
                                <button class="btn btn-primary btn-sm" id="editBtn_${product.id}" onclick="enableEdit(${product.id})">Edit</button>
                                <button class="btn btn-success btn-sm d-none" id="saveBtn_${product.id}" onclick="saveChanges(${product.id})">Save</button>
                                <button class="btn btn-danger btn-sm" onclick="removeProduct(${product.id})">Remove</button>
                            </td>
                        </tr>`;
                    });

                    // Update the table body with the generated rows
                    $('#productList').html(rows);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching products:", xhr.responseText);
                }
            });
        }

        function openCustomImageModal(src) {
            const modal = document.getElementById("customImageModal");
            const modalImg = document.getElementById("customModalImg");
            modal.style.display = "block";
            modalImg.src = src;
        }

        function closeCustomImageModal() {
            document.getElementById("customImageModal").style.display = "none";
        }

        function addToCart(productId) {
            let quantity = 1;
            let userId = 1;

            $.ajax({
                url: base_url + 'products/addToCart',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    user_id: userId,
                    product_id: productId,
                    quantity: quantity
                }),
                success: function(response) {
                    console.log("Product added to cart:", response);
                    alert("Product added to cart successfully!");
                },
                error: function(xhr, status, error) {
                    console.error("Error adding product to cart:", error);
                    alert("Failed to add product to cart. Please try again.");
                }
            });
        }

        function enableEdit(productId) {
            // Hide the text and show the input fields
            $(`#productName_${productId}`).addClass('d-none');
            $(`#productNameInput_${productId}`).removeClass('d-none');
            $(`#descriptionText_${productId}`).addClass('d-none');
            $(`#descriptionInput_${productId}`).removeClass('d-none');
            $(`#priceText_${productId}`).addClass('d-none');
            $(`#priceInput_${productId}`).removeClass('d-none');

            // Hide the Edit button and show the Save button
            $(`#editBtn_${productId}`).addClass('d-none');
            $(`#saveBtn_${productId}`).removeClass('d-none');
        }

        function saveChanges(productId) {
            // Get the updated values
            let newProductName = $(`#productNameInput_${productId}`).val();
            let newDescription = $(`#descriptionInput_${productId}`).val();
            let newPrice = $(`#priceInput_${productId}`).val();

            // Prepare the data to send
            let data = {
                productName: newProductName,
                description: newDescription,
                price: newPrice
            };

            // Send the data to the backend
            $.ajax({
                url: base_url + '/products/updateProduct/' + productId,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    alert('Product updated successfully');

                    // Update the table with the new values
                    $(`#productName_${productId}`).text(newProductName).removeClass('d-none');
                    $(`#productNameInput_${productId}`).addClass('d-none');
                    $(`#descriptionText_${productId}`).text(newDescription).removeClass('d-none');
                    $(`#descriptionInput_${productId}`).addClass('d-none');
                    $(`#priceText_${productId}`).text(newPrice).removeClass('d-none');
                    $(`#priceInput_${productId}`).addClass('d-none');

                    // Hide the Save button and show the Edit button
                    $(`#saveBtn_${productId}`).addClass('d-none');
                    $(`#editBtn_${productId}`).removeClass('d-none');
                },
                error: function(xhr, status, error) {
                    console.error("Error updating product:", xhr.responseText);
                }
            });
        }

        window.removeProduct = function(id) {
            if (confirm('Are you sure you want to remove this item?')) {
                $.ajax({
                    url: base_url + 'products/remove/' + id,
                    type: 'POST',
                    success: function(response) {
                        alert(response.message);
                        fetchProducts();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error updating item:", error);
                        alert("Failed to update item.");
                    }
                });
            }
        };
    </script>

</body>

</html>