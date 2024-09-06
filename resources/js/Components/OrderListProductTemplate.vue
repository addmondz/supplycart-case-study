<template>
    <div class="border p-4 my-4 rounded hover:border-violet-600 transition-all">
        <div class="grid grid-cols-5 gap-x-4 flex">
            <div class="col flex-1">
                <div class="row">
                    <div class="col-auto pr-10">
                        <div class="mb-1 text-xs text-gray-500">Brand / Category</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class=""><span class="font-extrabold uppercase">{{ product.brand.name}}</span> &nbsp; / &nbsp; {{ product.category.name }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col-auto pr-10">
                        <div class="mb-1 text-xs text-gray-500">Product Name</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="capitalize">{{ product.name }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col-auto pr-10">
                        <div class="mb-1 text-xs text-gray-500">Single Price</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="capitalize">$ {{ formatNumber(orderItem.product_price) }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col-auto pr-10">
                        <div class="mb-1 text-xs text-gray-500">Quantity</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="capitalize">{{ orderItem.quantity }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col-auto pr-10">
                        <div class="mb-1 text-xs text-gray-500">Total</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="capitalize">$ {{ orderItem.sum }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps } from 'vue';

const props = defineProps({
    orderItem: {
        type: Object,
        required: true,
    },
});

const product = props.orderItem.product;

const formatNumber = (number) => {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

</script>
