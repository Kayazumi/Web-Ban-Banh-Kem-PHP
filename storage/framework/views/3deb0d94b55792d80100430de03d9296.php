<?php $__env->startSection('title', 'Giỏ hàng - La Cuisine Ngọt'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Giỏ hàng của bạn</h1>

            <div id="cartContent">
                <!-- Cart items will be loaded via AJAX -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Đang tải...</span>
                    </div>
                    <p>Đang tải giỏ hàng...</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 20px;
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 10px;
    margin-bottom: 1rem;
    background: white;
}

.cart-item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 1rem;
}

.cart-item-details {
    flex: 1;
}

.cart-item-name {
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.cart-item-price {
    color: #c4a574;
    font-weight: bold;
}

.cart-quantity {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.cart-quantity button {
    width: 30px;
    height: 30px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 3px;
    cursor: pointer;
}

.cart-quantity input {
    width: 60px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 3px;
    padding: 0.25rem;
}

.cart-total {
    text-align: right;
    font-size: 1.2rem;
    font-weight: bold;
    color: #c4a574;
    margin-top: 2rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.checkout-btn {
    background: #c4a574;
    color: white;
    padding: 1rem 2rem;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
    cursor: pointer;
    margin-top: 1rem;
}

.checkout-btn:hover {
    background: #c4a574;
}

.empty-cart {
    text-align: center;
    padding: 3rem;
}

.empty-cart h3 {
    color: #6c757d;
    margin-bottom: 1rem;
}

.btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-primary {
    background: #c4a574;
    color: white;
    border: none;
}

.btn-primary:hover {
    background: #c4a574;
}

.text-center {
    text-align: center;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
});

async function loadCart() {
    try {
        const response = await fetch('/api/cart');
        const data = await response.json();

        const cartContent = document.getElementById('cartContent');

        if (data.success && data.data.cart_items.length > 0) {
            let html = '';

            data.data.cart_items.forEach(item => {
                html += `
                    <div class="cart-item">
                        <img src="${item.product?.image_url || '/images/placeholder.jpg'}"
                             alt="${item.product?.product_name || 'Sản phẩm'}"
                             class="cart-item-image">
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.product?.product_name || 'Sản phẩm'}</div>
                            <div class="cart-item-price">${formatPrice(item.product?.price || 0)}</div>
                        </div>
                        <div class="cart-quantity">
                            <button onclick="updateQuantity(${item.CartID}, ${item.quantity - 1})">-</button>
                            <input type="number" value="${item.quantity}" min="1"
                                   onchange="updateQuantity(${item.CartID}, this.value)">
                            <button onclick="updateQuantity(${item.CartID}, ${item.quantity + 1})">+</button>
                        </div>
                        <div class="cart-item-subtotal">
                            <strong>${formatPrice(item.subtotal || 0)}</strong>
                        </div>
                        <button onclick="removeItem(${item.CartID})" class="remove-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });

            html += `
                <div class="cart-total">
                    <p>Tổng cộng: <span id="cartTotal">${formatPrice(data.data.total)}</span></p>
                    <button class="checkout-btn" onclick="proceedToCheckout()">Tiến hành đặt hàng</button>
                </div>
            `;

            cartContent.innerHTML = html;
        } else {
            cartContent.innerHTML = `
                <div class="empty-cart">
                    <h3>Giỏ hàng trống</h3>
                    <p>Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                    <a href="/" class="btn btn-primary">Tiếp tục mua sắm</a>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        document.getElementById('cartContent').innerHTML = `
            <div class="text-center">
                <p>Có lỗi xảy ra khi tải giỏ hàng. Vui lòng thử lại.</p>
                <button onclick="loadCart()" class="btn btn-primary">Thử lại</button>
            </div>
        `;
    }
}

async function updateQuantity(cartId, quantity) {
    if (quantity < 1) return;

    try {
        const response = await fetch(`/api/cart/${cartId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            body: JSON.stringify({ quantity })
        });

        const data = await response.json();

        if (data.success) {
            loadCart(); // Reload cart
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error updating quantity:', error);
        alert('Có lỗi xảy ra');
    }
}

async function removeItem(cartId) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) return;

    try {
        const response = await fetch(`/api/cart/${cartId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            }
        });

        const data = await response.json();

        if (data.success) {
            loadCart(); // Reload cart
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Error removing item:', error);
        alert('Có lỗi xảy ra');
    }
}

function proceedToCheckout() {
    // For now, redirect to orders page
    // In a real app, this would go to checkout
    window.location.href = '/orders';
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/cart.blade.php ENDPATH**/ ?>