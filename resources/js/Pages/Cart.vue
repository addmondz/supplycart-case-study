<template>

    <Head title="Cart" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cart</h2>
        </template>
        <div class="relative min-h-screen">
            <div class="pb-36">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <h6 class="font-manrope font-semibold text-3xl w-100 mb-5">
                            <span class="text-lg inline-block">Showing:</span> {{ cartData.length }}
                            <span class="text-lg inline-block"> Item(s)</span>
                        </h6>
                        <div v-if="cartData.length" class="grid md:grid-cols gap-4 mb-5">
                            <CartListTemplate v-for="cart in cartData" :key="cart.id" :cart="cart"
                                @cart-checked="handleCartChecked" @quantity-updated="handleQuantityUpdated" />
                        </div>
                        <div v-else>
                            <NotFound />
                        </div>
                    </div>
                </div>
            </div>
            <!-- cart footer -->
            <div class="fixed bottom-0 left-0 w-full bg-slate-800 text-white p-4 h-40">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-36">
                    <div class="p-2 flex justify-between items-center">
                        <h6 class="font-manrope font-semibold text-3xl w-100">
                            <span class="text-lg inline-block">Cart Selected: </span> {{ selectedCarts.length }}
                            <span class="text-lg inline-block"> Item(s)</span>
                        </h6>
                        <h6 class="font-manrope font-semibold text-3xl w-100">
                            <span class="text-lg inline-block mr-2">Item Total: </span> ${{ formatPrice(itemTotal) }}
                        </h6>
                    </div>
                    <div class="p-2 flex justify-end items-center">
                        <button @click="createOrder"
                            class="rounded-md bg-indigo-500 py-2.5 px-5 border border-transparent text-center text-base text-white transition-all hover:bg-indigo-600 text-xl px-12"
                            type="button" :disabled="!selectedCarts.length"
                            :class="[{ 'cursor-not-allowed': !selectedCarts.length }]">
                            Create Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import CartListTemplate from '@/Components/CartListTemplate.vue';
import Swal from 'sweetalert2';
import NotFound from '@/Components/Icons/NotFound.vue';
import { formatPrice, apiBaseUrl, getPriceAfterDiscount, formatNumberToTwoDecimals } from '@/Helpers/helpers.js';

const cartData = ref([]);
const error = ref(null);
const selectedCarts = ref([]);
const itemTotal = ref(0);
const apiUrl = apiBaseUrl + 'api/cart';

const fetchProducts = async () => {
    try {
        const { data } = await axios.get(apiUrl);
        cartData.value = data;
    } catch (err) {
        error.value = err;
    }
};

onMounted(fetchProducts);

const handleCartChecked = (cartId, isSelected, cart, quantity) => {
    if (isSelected) {
        // Add the cart if it's not already in the selectedCarts array
        if (!selectedCarts.value.some(c => c.id === cartId)) {
            selectedCarts.value.push({ ...cart, quantity: Number(quantity) });
        }
    } else {
        // Remove the cart by filtering out the unselected one
        selectedCarts.value = selectedCarts.value.filter(c => c.id !== cartId);
    }
    calculateItemTotal();
};

const handleQuantityUpdated = (cartId, newQuantity) => {
    const cartIndex = selectedCarts.value.findIndex(c => c.id === cartId);
    if (cartIndex !== -1) {
        selectedCarts.value[cartIndex].quantity = Number(newQuantity);
    }
    calculateItemTotal();
};

const calculateItemTotal = () => {
    itemTotal.value = selectedCarts.value.reduce((total, { product: { price }, quantity }) => {
        let itemTotal = getPriceAfterDiscount(price, 'number') * quantity;
        return formatNumberToTwoDecimals(total) + formatNumberToTwoDecimals(itemTotal);
    }, 0);
};

const createOrder = async () => {
    if (!Array.isArray(selectedCarts.value) || !selectedCarts.value.length) {
        return '';
    }

    try {
        const requestBody = {
            carts: selectedCarts.value.map(cart => ({
                cart_id: cart.id,
                quantity: cart.quantity,
            })),
        };

        await axios.post(apiBaseUrl + 'api/order', requestBody);

        fetchProducts();
        itemTotal.value = 0;
        selectedCarts.value = [];

        Swal.fire({
            title: 'Success!',
            text: 'Order created successfully!',
            icon: 'success',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'w-32'
            }
        });
    } catch (error) {
        console.error('Error creating order:', error);

        Swal.fire({
            title: 'Error!',
            text: error.response?.data?.message || 'Failed to create order!',
            icon: 'error',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'w-32'
            }
        });
    }
};
</script>