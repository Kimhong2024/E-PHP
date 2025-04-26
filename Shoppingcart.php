 <!-- Shopping Cart Section -->
 <section id="shopping-cart" class="container mx-auto my-12 px-4">
        <h2 class="text-3xl font-bold text-center mb-8">Shopping Cart</h2>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-6">Cart Items</h3>
                    <div id="cartItems" class="space-y-4">
                        <!-- Cart items will be dynamically added here -->
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold mb-6">Order Summary</h3>
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
                    <button id="checkoutButton" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </section>