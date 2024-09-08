<template>
    <div class="w-full flex flex-col justify-center order-last lg:order-none max-lg:mx-auto border p-10 rounded-xl overflow-hidden border-white hover:border-violet-600 transition-all duration-500"
        :class="[{ 'bg-slate-300': checked }, { 'bg-white': !checked }]">
        <div class="flex">
            <div class="flex justify-center items-center mr-10">
                <Checkbox name="remember" v-model:checked="checked" class="h-7 w-7" />
            </div>
            <div class="flex-1">
                <p class="font-medium text-lg text-indigo-600 mb-4"><span class="font-extrabold uppercase">{{
                    product.brand.name
                        }}</span> &nbsp; / &nbsp; {{ product.category.name }}</p>
                <h2 class="mb-2 font-manrope font-bold text-3xl leading-10 text-gray-900 capitalize break-words">
                    {{ product.name }}
                </h2>
                <div class="flex flex-col sm:flex-row items-bottom justify-start mb-6">
                    <h6 class="font-manrope font-semibold text-2xl leading-9 text-gray-900 pr-5 mr-5">
                        <span v-if="$page.props.auth.user.membership_type_id != 1">
                            <span v-if="storedUserData">
                                <span class="line-through text-base text-gray-400">${{ formatPrice(product.price)
                                    }}</span>
                                <label v-if="storedUserData.membership_type"
                                    :class="[storedUserData.membership_type.color ?? '']"
                                    class="inline-block font-medium text-sm bg-gray-200 rounded-lg px-3 py-1 ml-2">
                                    {{ storedUserData.membership_type.discount }} % OFF
                                </label>
                                <br>
                            </span>
                        </span>
                        ${{ getPriceAfterDiscount(product.price) }}
                    </h6>
                </div>
                <div class="block w-full">
                    <div class="flex grid md:grid-cols-2">
                        <div class="flex align-bottom items-end w-100">
                            <h6 class="font-manrope font-semibold text-2xl text-gray-900 pr-5 mr-5 w-100">
                                <span class="text-lg inline-block mr-2">Item Total: </span> ${{ formatPrice(itemTotal)
                                }}
                            </h6>
                        </div>
                        <div class="flex items-center justify-end w-full">
                            <button @click="decreaseCount"
                                class="group py-4 px-6 border border-gray-400 rounded-l-full shadow-sm shadow-transparent transition-all duration-500 hover:shadow-gray-300">
                                <svg class="stroke-gray-700 transition-all duration-500 group-hover:stroke-black"
                                    width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.5 11H5.5" stroke="#000" stroke-width="1.6" stroke-linecap="round" />
                                </svg>
                            </button>
                            <input type="text"
                                class="font-semibold text-gray-900 text-lg py-[13px] px-6 w-full lg:max-w-[118px] border-y border-gray-400 bg-transparent placeholder:text-gray-900 text-center focus-within:bg-gray-50 outline-0"
                                placeholder="1" v-model="productCount">
                            <button @click="increaseCount"
                                class="group py-4 px-6 border border-gray-400 rounded-r-full shadow-sm shadow-transparent transition-all duration-500 hover:shadow-gray-300">
                                <svg class="stroke-gray-700 transition-all duration-500 group-hover:stroke-black"
                                    width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 5.5V16.5M16.5 11H5.5" stroke="#000" stroke-width="1.6"
                                        stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, defineProps, watch, defineEmits } from 'vue';
import axios from 'axios';
import Checkbox from '@/Components/Checkbox.vue';
import { formatPrice, apiBaseUrl, storedUserData, getPriceAfterDiscount } from '@/Helpers/helpers.js';

const props = defineProps({
    cart: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits();
const product = ref(props.cart.product);
const productCount = ref(props.cart.quantity);
const checked = ref(false);

const itemTotal = computed(() => {
    const total = getPriceAfterDiscount(props.cart.product.price, 'number') * productCount.value;

    return total;
});

const increaseCount = () => {
    productCount.value++;
};

const decreaseCount = () => {
    productCount.value > 1 ? productCount.value-- : productCount.value = 1;
};

watch(productCount, () => {
    updateQuantity();
    emit('quantity-updated', props.cart.id, productCount.value);
});

watch(checked, (newValue) => {
    emit('cart-checked', props.cart.id, newValue, props.cart, productCount.value);
});

const updateQuantity = async () => {
    try {
        await axios.put(apiBaseUrl + 'api/cart/update-quantity', {
            cart_id: props.cart.id,
            quantity: productCount.value,
        });
    } catch (error) {
        console.error(error);
    }
};
</script>
