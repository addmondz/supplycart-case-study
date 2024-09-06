<template>

    <Head title="Products" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
        </template>
        <div class="py-12" v-if="apiResponse">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-4 mb-5">
                    <FilterDropdown class="w-full mr-5" label="a brand" v-model="brandFilter" :options="brandData" />
                    <FilterDropdown class="w-full mr-5" label="a category" v-model="categoryFilter"
                        :options="categoryData" />
                    <button class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded ml-2"
                        @click="resetFilters">Reset
                        Filters</button>
                    <button class="bg-violet-500 hover:bg-violet-700 text-white py-2 px-4 rounded ml-2"
                        @click="applyFilters">Search</button>
                </div>
                <div class="grid md:grid-cols-3 gap-4 mb-5">
                    <ProductListTemplate v-for="product in productData" :key="product.id" :product="product" />
                </div>
                <div v-if="productData.length === 0">
                    <NotFound />
                </div>
                <div class="flex justify-between w-full">
                    <div>
                        <p>Showing {{ apiResponse.from ?? 0 }} to {{ apiResponse.to ?? 0 }} of {{ apiResponse.total }}
                            entries
                        </p>
                    </div>
                    <div class="flex justify-end flex-1">
                        <button class="bg-white hover:bg-gray-200 text-gray py-2 px-4 rounded ml-2" @click="prevPage"
                            :disabled="currentPage == 1" :class="[{ 'cursor-not-allowed': currentPage == 1 }]">Previous
                            Page</button>
                        <button class="bg-black hover:bg-gray-700 text-white py-2 px-4 rounded ml-2"
                            :disabled="currentPage == lastPage"
                            :class="[{ 'cursor-not-allowed': currentPage == lastPage }]" @click="nextPage">Next
                            Page</button>
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
import ProductListTemplate from '@/Components/ProductListTemplate.vue';
import NotFound from '@/Components/Icons/NotFound.vue';
import FilterDropdown from '@/Components/FilterDropdown.vue';

const productData = ref([]);
const categoryData = ref([]);
const brandData = ref([]);
const error = ref(null);
const brandFilter = ref(null);
const categoryFilter = ref(null);
const apiResponse = ref(null);
const limit = ref(9);
const currentPage = ref(1);
const lastPage = ref(0);
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
const apiUrl = apiBaseUrl + 'api/product';

// Fetch products with filters applied
const fetchProducts = async (page = 1) => {
    try {
        const queryParams = new URLSearchParams({
            limit: limit.value,
            page: page,
            brand: brandFilter.value ?? '',
            category: categoryFilter.value ?? '',
        }).toString();

        const { data } = await axios.get(`${apiUrl}?${queryParams}`);
        productData.value = data.data;
        apiResponse.value = data;
        lastPage.value = data.last_page;
    } catch (err) {
        error.value = err;
    }
};

// Fetch categories and brands
const fetchCategories = async () => {
    try {
        const { data } = await axios.get(apiBaseUrl + 'api/category');
        categoryData.value = data;
    } catch (err) {
        error.value = err;
    }
};

const fetchBrands = async () => {
    try {
        const { data } = await axios.get(apiBaseUrl + 'api/brand');
        brandData.value = data;
    } catch (err) {
        error.value = err;
    }
};

// On component mount, load data
onMounted(() => {
    fetchProducts(currentPage.value);
    fetchCategories();
    fetchBrands();
});

// Pagination functions
const nextPage = () => {
    if (currentPage.value >= lastPage.value) return;
    currentPage.value += 1;
    fetchProducts(currentPage.value);
};

const prevPage = () => {
    if (currentPage.value <= 1) return;
    currentPage.value -= 1;
    fetchProducts(currentPage.value);
};

// Apply filters and reset filters
const applyFilters = () => {
    currentPage.value = 1;
    fetchProducts(currentPage.value);
};

const resetFilters = () => {
    brandFilter.value = null;
    categoryFilter.value = null;
    applyFilters();
};
</script>
