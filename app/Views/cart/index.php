<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</head>

<body>
    <div class="container mt-5">

        <button id="link-cart" class="btn btn-primary" onclick="window.location.href='<?= base_url('product') ?>'">Go To Produect</button>

        <h2 class="mb-4">Cart Management</h2>

        <!-- Cart List -->
        <h3>Cart Items</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody id="cartList">
                <!-- Cart items will be loaded here -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><strong>Total:</strong></td>
                    <td colspan="4"><strong id="totalPrice">$0.00</strong></td>
                </tr>

            </tfoot>
        </table>
    </div>

    <script>
        var base_url = "<?php echo base_url(); ?>";
    </script>


    <script>
        function enableEdit(id) {
            let input = $('#qtyInput_' + id);
            let saveBtn = $('#saveBtn_' + id);
            input.removeClass('d-none');
            $('#qtyText_' + id).addClass('d-none');
            $('#editBtn_' + id).addClass('d-none');
            saveBtn.removeClass('d-none');

            // Enable save button only if the value changes
            input.on('input', function() {
                saveBtn.prop('disabled', $(this).val() == input.attr('value'));
            });
        }

        function updateTotalPrice() {
            let total = 0;
            $('#cartList tr').each(function() {
                let price = parseFloat($(this).find('td:nth-child(3)').text());
                let qty = parseInt($(this).find('#qtyText_' + $(this).find('td:first').text()).text());
                total += price * qty;
            });
            $('#totalPrice').text('$' + total.toFixed(2));
        }


        function saveQuantity(id) {
            let newQuantity = $('#qtyInput_' + id).val();

            $.ajax({
                url: base_url + 'cart/updateQuantity/' + id,
                type: 'POST',
                data: {
                    quantity: newQuantity
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        fetchCartItems(); // Refresh cart
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            // Fetch cart items on page load
            fetchCartItems();
            updateTotalPrice();
        });
        // Fetch cart items function
        function fetchCartItems() {
            $.ajax({
                url: base_url + 'cart/list',
                type: 'GET',
                success: function(response) {
                    let rows = '';
                    let total = 0;

                    response.forEach(item => {
                        const itemTotal = parseFloat(item.price) * parseInt(item.quantity);
                        total += itemTotal;

                        rows += `<tr>
                    <td>${item.id}</td>
                    <td>${item.product_name}</td>
                    <td>${parseFloat(item.price).toFixed(2)}</td>
                    <td>
                        <span id="qtyText_${item.id}">${item.quantity}</span>
                        <input type="number" id="qtyInput_${item.id}" value="${item.quantity}" min="1" 
                            class="form-control d-none" style="width: 60px;">
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm" id="editBtn_${item.id}" onclick="enableEdit(${item.id})">Edit</button>
                        <button class="btn btn-success btn-sm d-none" id="saveBtn_${item.id}" onclick="saveQuantity(${item.id})">Save</button>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${item.id})">Remove</button>
                    </td>
                </tr>`;
                    });

                    $('#cartList').html(rows);
                    $('#totalPrice').text(`${total.toFixed(2)}`);
                }
            });
        }


        window.updateQuantity = function(id, newQuantity) {
            $.ajax({
                url: base_url + 'cart/updateQuantity/' + id,
                type: 'POST',
                data: {
                    quantity: newQuantity
                }, // Send new quantity
                success: function(response) {
                    alert(response.message);
                    fetchCartItems(); // Refresh cart items
                },
                error: function(xhr, status, error) {
                    console.error("Error updating quantity:", error);
                    alert("Failed to update quantity.");
                }
            });
        };

        // Remove item from cart function
        window.removeFromCart = function(id) {
            if (confirm('Are you sure you want to remove this item?')) {
                $.ajax({
                    url: base_url + 'cart/remove/' + id,
                    type: 'POST',
                    success: function(response) {
                        alert(response.message);
                        fetchCartItems(); // Refresh cart items
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