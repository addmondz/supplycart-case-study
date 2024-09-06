<template>
    <div
        class="pro-detail w-full flex flex-col justify-center order-last lg:order-none max-lg:max-w-[608px] max-lg:mx-auto border p-10 rounded-xl bg-white overflow-hidden">
        <p class="font-medium text-lg text-indigo-600 mb-4"><span class="font-extrabold uppercase">{{ product.brand.name
                }}</span> &nbsp; / &nbsp; {{ product.category.name }}</p>
        <h2 class="mb-2 font-manrope font-bold text-3xl leading-10 text-gray-900 capitalize break-words">{{ product.name
            }}</h2>
        <div class="flex-auto">
            <!-- takes up the vertical space -->
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center mb-6">
            <h6 class="font-manrope font-semibold text-2xl leading-9 text-gray-900 pr-5 mr-5">
                ${{ product.price }}
            </h6>
        </div>
        <div class="block w-full">
            <div class="text">
                <div class="flex items-center justify-center w-full mb-5">
                    <button @click="decreaseCount"
                        class="group py-4 px-6 border border-gray-400 rounded-l-full shadow-sm shadow-transparent transition-all duration-500 hover:shadow-gray-300 hover:bg-gray-50">
                        <svg class="stroke-gray-700 transition-all duration-500 group-hover:stroke-black" width="22"
                            height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.5 11H5.5" stroke="" stroke-width="1.6" stroke-linecap="round" />
                            <path d="M16.5 11H5.5" stroke="" stroke-opacity="0.2" stroke-width="1.6"
                                stroke-linecap="round" />
                            <path d="M16.5 11H5.5" stroke="" stroke-opacity="0.2" stroke-width="1.6"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                    <input type="text"
                        class="font-semibold text-gray-900 text-lg py-[13px] px-6 w-full lg:max-w-[118px] border-y border-gray-400 bg-transparent placeholder:text-gray-900 text-center hover:bg-gray-50 focus-within:bg-gray-50 outline-0"
                        placeholder="1" v-model="productCount">
                    <button @click="increaseCount"
                        class="group py-4 px-6 border border-gray-400 rounded-r-full shadow-sm shadow-transparent transition-all duration-500 hover:shadow-gray-300 hover:bg-gray-50">
                        <svg class="stroke-gray-700 transition-all duration-500 group-hover:stroke-black" width="22"
                            height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11 5.5V16.5M16.5 11H5.5" stroke="" stroke-width="1.6" stroke-linecap="round" />
                            <path d="M11 5.5V16.5M16.5 11H5.5" stroke="" stroke-opacity="0.2" stroke-width="1.6"
                                stroke-linecap="round" />
                            <path d="M11 5.5V16.5M16.5 11H5.5" stroke="" stroke-opacity="0.2" stroke-width="1.6"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <button @click="addToCart"
                    class="group py-4 px-5 rounded-full bg-indigo-50 text-indigo-600 font-semibold text-lg w-full flex items-center justify-center gap-2 shadow-sm shadow-transparent transition-all duration-500 hover:bg-indigo-100 hover:shadow-indigo-200">
                    <svg class="stroke-indigo-600 transition-all duration-500" width="22" height="22"
                        viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.7394 17.875C10.7394 18.6344 10.1062 19.25 9.32511 19.25C8.54402 19.25 7.91083 18.6344 7.91083 17.875M16.3965 17.875C16.3965 18.6344 15.7633 19.25 14.9823 19.25C14.2012 19.25 13.568 18.6344 13.568 17.875M4.1394 5.5L5.46568 12.5908C5.73339 14.0221 5.86724 14.7377 6.37649 15.1605C6.88573 15.5833 7.61377 15.5833 9.06984 15.5833H15.2379C16.6941 15.5833 17.4222 15.5833 17.9314 15.1605C18.4407 14.7376 18.5745 14.0219 18.8421 12.5906L19.3564 9.84059C19.7324 7.82973 19.9203 6.8243 19.3705 6.16215C18.8207 5.5 17.7979 5.5 15.7522 5.5H4.1394ZM4.1394 5.5L3.66797 2.75"
                            stroke="" stroke-width="1.6" stroke-linecap="round" />
                    </svg>
                    Add to cart</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const productCount = ref(1);
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;

const increaseCount = () => {
    productCount.value++;
};

const decreaseCount = () => {
    productCount.value > 1 ? productCount.value-- : '';
};

const props = defineProps({
    product: {
        type: Object,
        required: true,
    }
});

const addToCart = async () => {
    try {
        const response = await axios.post(apiBaseUrl + 'api/product/add-to-cart', {
            product_id: props.product.id,
            quantity: productCount.value,
        });

        Swal.fire({
            title: 'Success!',
            text: 'Product added to cart!',
            icon: 'success',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'w-32'
            }
        });

        // Reset productCount value to 1 after adding to cart
        productCount.value = 1;
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: 'Failed to add product to cart!',
            icon: 'error',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'w-32'
            }
        });
    }
};
</script>