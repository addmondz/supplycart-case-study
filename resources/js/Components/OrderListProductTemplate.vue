<template>
    <div class="border p-4 my-4 rounded hover:border-violet-600 transition-all">
        <div class="grid gap-x-4 flex"
        :class="[{ 'grid-cols-6': orderItem.discount != 0 }, { 'grid-cols-5': orderItem.discount == 0 }]">
            <div class="col flex-1">
                <div class="row">
                    <div class="col-auto pr-10">
                        <div class="mb-1 text-xs text-gray-500">Brand / Category</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class=""><span class="font-extrabold uppercase">{{ product.brand.name }}</span> &nbsp; /
                            &nbsp; {{ product.category.name }}</div>
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
            <div class="col" v-if="orderItem.discount != 0">
                <div class="row">
                    <div class="col-auto pr-10">
                        <div class="mb-1 text-xs text-gray-500">Discount</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="capitalize">{{ orderItem.discount }} %</div>
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
                        <div class="capitalize" v-if="orderItem.discount == 0">
                            $ {{ formatPrice(orderItem.product_original_price) }}
                        </div>
                        <div class="capitalize" v-else>
                            $ {{ formatPrice(orderItem.product_price_after_discount) }}
                            <span class="line-through text-base text-gray-400 text-xs">
                                ${{ formatPrice(orderItem.product_original_price) }}
                            </span>
                        </div>
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
import { formatPrice } from '@/Helpers/helpers.js';

const props = defineProps({
    orderItem: {
        type: Object,
        required: true,
    },
});

const product = props.orderItem.product;
</script>
