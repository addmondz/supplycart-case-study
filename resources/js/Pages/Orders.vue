<template>

    <Head title="Orders" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Orders</h2>
        </template>
        <div class="relative min-h-screen">
            <div class="pb-36">
                <div class="py-12" v-if="apiResponse">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div v-if="orderData.length" class="grid md:grid-cols gap-4 mb-5">
                            <OrderListTemplate v-for="order in orderData" :key="order.id" :order="order" />
                        </div>
                        <div v-else>
                            <NotFound />
                        </div>
                        <div class="flex justify-betweeen w-full">
                            <div>
                                <p>Showing {{ apiResponse.from ?? 0 }} to {{ apiResponse.to ?? 0 }} of {{
                                    apiResponse.total }}
                                    entries</p>
                            </div>
                            <div class="flex justify-end flex-1">
                                <button class="bg-white hover:bg-gray-200 text-gray py-2 px-4 rounded ml-2"
                                    @click="prevPage" :disabled="currentPage == 1"
                                    :class="[{ 'cursor-not-allowed': currentPage == 1 }]">Previous
                                    Page</button>
                                <button class="bg-black hover:bg-gray-700 text-white py-2 px-4 rounded ml-2"
                                    :disabled="currentPage == lastPage"
                                    :class="[{ 'cursor-not-allowed': currentPage == lastPage }]" @click="nextPage">Next
                                    Page</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import OrderListTemplate from '@/Components/OrderListTemplate.vue';
import NotFound from '@/Components/Icons/NotFound.vue';

const orderData = ref([]);
const error = ref(null);
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
const apiUrl = apiBaseUrl + 'api/order';
const limit = ref(10);
const currentPage = ref(1);
const lastPage = ref(0);
const apiResponse = ref(null);

const fetchOrders = async (page = 1) => {
    try {
        const { data } = await axios.get(`${apiUrl}?limit=${limit.value}&page=${page}`);
        orderData.value = data.data;
        apiResponse.value = data;
        lastPage.value = data.last_page;
    } catch (err) {
        error.value = err;
    }
};

const nextPage = () => {
    console.log(lastPage.value);
    if (currentPage.value >= lastPage.value) return;
    currentPage.value += 1;
    fetchOrders(currentPage.value);
};

const prevPage = () => {
    if (currentPage.value <= 1) return;
    currentPage.value -= 1;
    fetchOrders(currentPage.value);
};

onMounted(fetchOrders);
</script>
