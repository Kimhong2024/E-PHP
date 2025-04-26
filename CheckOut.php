<div class="container mx-auto p-8">
    <!-- Checkout Section -->
    <section class="checkout-container my-12 px-4">
        <h1 class="text-3xl font-bold text-center mb-8">Checkout</h1>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Shipping and Payment Information -->
            <div class="lg:col-span-2 bg-white p-8 rounded-lg shadow-lg checkout-form">
                <h2 class="text-xl font-semibold mb-6">Shipping Information</h2>
                <form>
                    <!-- Full Name -->
                    <div class="mb-6">
                        <label for="fullName" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="fullName" name="fullName" class="w-full p-2 border rounded" placeholder="Enter your full name" required>
                    </div>

                    <!-- Address -->
                    <div class="mb-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" id="address" name="address" class="w-full p-2 border rounded" placeholder="Enter your address" required>
                    </div>

                    <!-- City -->
                    <div class="mb-6">
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input type="text" id="city" name="city" class="w-full p-2 border rounded" placeholder="Enter your city" required>
                    </div>

                    <!-- Country and Zip Code -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <select id="country" name="country" class="w-full p-2 border rounded" required>
                                <option value="">Select Country</option>
                                <option value="US">United States</option>
                                <option value="CA">Cambodia</option>
                                <option value="UK">Thai</option>
                                <option value="AU">Vietnam</option>
                            </select>
                        </div>
                        <div>
                            <label for="zipCode" class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                            <input type="text" id="zipCode" name="zipCode" class="w-full p-2 border rounded" placeholder="Enter your zip code" required>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <h2 class="text-xl font-semibold mb-6">Payment Information</h2>
                    <div class="mb-6">
                        <label for="cardNumber" class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                        <input type="text" id="cardNumber" name="cardNumber" class="w-full p-2 border rounded" placeholder="Enter card number" required>
                    </div>

                    <!-- Expiration and CVV -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="expiration" class="block text-sm font-medium text-gray-700 mb-2">Expiration Date</label>
                            <input type="text" id="expiration" name="expiration" class="w-full p-2 border rounded" placeholder="MM/YY" required>
                        </div>
                        <div>
                            <label for="cvv" class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                            <input type="text" id="cvv" name="cvv" class="w-full p-2 border rounded" placeholder="Enter CVV" required>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out">
                        Place Order
                    </button>
                </form>
            </div>

            <!-- Right Column: Order Summary and PayPal Button -->
            <div class="bg-white p-8 rounded-lg shadow-lg checkout-summary">
                <h2 class="text-xl font-semibold mb-6">Order Summary</h2>
                <div id="cartItems" class="space-y-4">
    <!-- Cart items will be dynamically inserted here -->
</div>
                <hr class="my-6">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-bold" id="subtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-bold">$0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-bold" id="tax">$0.00</span>
                    </div>
                    <hr class="my-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-bold">Total</span>
                        <span class="text-blue-500 font-bold text-xl" id="cartTotal">$0.00</span>
                    </div>
                </div>

                <!-- PayPal Button -->
                <div id="paypal-button-container" class="mt-6"></div>
            </div>
        </div>
    </section>
</div>


<script>


function loadCart() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItems = document.getElementById('cartItems');
    cartItems.innerHTML = ''; // Clear existing items

    let subtotal = 0;
    cart.forEach((item) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        const cartItem = document.createElement('div');
        cartItem.classList.add('flex', 'justify-between', 'items-center');
        cartItem.innerHTML = `
            <div class="flex items-center">
                <img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded-md mr-4">
                <div>
                    <h3 class="text-lg font-semibold">${item.name}</h3>
                    <p class="text-gray-500">Qty: ${item.quantity}</p>
                </div>
            </div>
            <span class="text-blue-500 font-bold">$${itemTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</span>
        `;
        cartItems.appendChild(cartItem);
    });

    const taxRate = 0.08; // 8% tax
    const tax = subtotal * taxRate;
    const total = subtotal + tax;

    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
    document.getElementById('tax').textContent = `$${tax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
    document.getElementById('cartTotal').textContent = `$${total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;

    return total; // Return the total for PayPal
}

// Load cart when the page loads
const totalAmount = loadCart();

// Render the PayPal button
paypal.Buttons({
    createOrder: function (data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: totalAmount.toFixed(2) // Total amount from the cart
                }
            }]
        });
    },
    onApprove: function (data, actions) {
        return actions.order.capture().then(function (details) {
            alert('Payment successful! Thank you, ' + details.payer.name.given_name + '.');
            // Clear the cart after successful payment
            localStorage.removeItem('cart');
            window.location.href = './Home.html'; // Redirect to home page
        });
    },
    onError: function (err) {
        console.error('Payment failed:', err);
        alert('Payment failed. Please try again.');
    }
}).render('#paypal-button-container');
</script>