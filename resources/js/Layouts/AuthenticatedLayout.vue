<script setup>
import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { fetchUserDetails } from '@/Helpers/helpers.js';

const showingNavigationDropdown = ref(false);
const { props } = usePage();

const userData = ref([]);
const loadUserData = async () => {
    let storedUserData = JSON.parse(sessionStorage.getItem('userData'));

    if (!storedUserData) {
        try {
            storedUserData = await fetchUserDetails();
            sessionStorage.setItem('userData', JSON.stringify(storedUserData));
        } catch (err) {
            console.error('Error fetching membership data:', err);
            return;
        }
    }

    userData.value = storedUserData;
};

const logout = () => {
    sessionStorage.clear();
    localStorage.clear();
    sessionStorage.removeItem('userData');
};

onMounted(loadUserData);
</script>

<template>
    <div>
        <div class="bg-violet-400 text-white bg-yellow-500 text-gray-500 hidden"></div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                            </Link>
                        </div>
                        <div class="hidden sm:-my-px sm:flex">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Products
                            </NavLink>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('cart')" :active="route().current('cart')">
                                Cart
                            </NavLink>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('orders')" :active="route().current('orders')">
                                Orders
                            </NavLink>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('activity_log')" :active="route().current('activity_log')">
                                Activity Log
                            </NavLink>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <div class="ms-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            {{ props.auth.user.name }}
                                            <label v-if="userData.membership_type"
                                                class="inline-block font-medium text-sm bg-gray-200 rounded-lg px-3 py-1 ml-2"
                                                :class="[userData.membership_type.color ?? '']">
                                                {{ userData.membership_type.name }}
                                            </label>
                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                    <DropdownLink @click="logout" :href="route('logout')" method="post" as="button">Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path
                                    :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('cart')" :active="route().current('cart')">Cart
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('orders')" :active="route().current('orders')">Orders
                        </ResponsiveNavLink>
                        <ResponsiveNavLink @click="logout" :href="route('activity_log')" :active="route().current('activity_log')">
                            Activity
                            Log</ResponsiveNavLink>
                    </div>
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ props.auth.user.name }}
                                <label v-if="userData.membership_type"
                                    class="inline-block font-medium text-sm bg-gray-200 rounded-lg px-3 py-1 ml-2"
                                    :class="[userData.membership_type.color ?? '']">
                                    {{ userData.membership_type.name }}
                                </label>
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ props.auth.user.email }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
