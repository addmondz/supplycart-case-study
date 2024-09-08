<template>
    <div
        class="w-full flex flex-col justify-center order-last lg:order-none max-lg:mx-auto border p-5 rounded-xl overflow-hidden bg-white">
        <div class="flex">
            <div class="flex-1">
                <div class="grid grid-cols-5 gap-x-4">
                    <div class="col">
                        <div class="row">
                            <div class="col-auto pr-10">
                                <div class="mb-1 text-xs text-gray-500">ID</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="">#{{ formatToSevenDigits(order.id) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto pr-10">
                                <div class="mb-1 text-xs text-gray-500">Order Date</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="">{{ formatDate(order.created_at) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto pr-10">
                                <div class="mb-1 text-xs text-gray-500">Total Price</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="">$ {{ formatPrice(order.total_price) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto pr-10">
                                <div class="mb-1 text-xs text-gray-500">No. Cart</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="">{{ order.items.length }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto pr-10">
                                <div class="mb-1 text-xs text-gray-500">Total Item(s)</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="">{{ itemTotalQuantity }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center items-center mr-10">
                <button @click="showDetails = !showDetails" class="cursor-pointer hover:text-violet-600 transition-all">
                    <AngleDown v-if="!showDetails" />
                    <AngleUp v-else />
                </button>
            </div>
        </div>
        <div class="transition-all duration-300 ease-in-out mt-2" v-show="showDetails">
            <OrderListProductTemplate v-for="item in order.items" :key="item.id" :orderItem="item" />
            <div v-if="!order.items.length">
                <div class="border p-4 my-4 rounded text-center bg-gray-100">
                    No items to display here.
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, computed, defineProps } from 'vue';
import moment from 'moment';
import AngleDown from '@/Components/Icons/AngleDown.vue';
import AngleUp from '@/Components/Icons/AngleUp.vue';
import OrderListProductTemplate from '@/Components/OrderListProductTemplate.vue';
import { formatPrice } from '@/Helpers/helpers.js';

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
});

const showDetails = ref(false);

const formatDate = (date) => {
    return moment(date).format('DD-MM-YYYY');
};

const formatToSevenDigits = (num) => {
    return num.toString().padStart(7, '0');
};

const itemTotalQuantity = computed(() => {
    return props.order.items.reduce((total, item) => {
        return total + item.quantity;
    }, 0);

    return 0;
});
</script>
