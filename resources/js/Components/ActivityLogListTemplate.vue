<template>
    <div
        class="w-full flex flex-col justify-center order-last lg:order-none max-lg:mx-auto border p-5 rounded-xl overflow-hidden bg-white">
        <div class="flex">
            <div class="flex-1">
                <div class="grid grid-cols-5 gap-x-4">
                    <div class="col">
                        <div class="row">
                            <div class="col-auto pr-10">
                                <div class="mb-1 text-xs text-gray-500">Date</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="">{{ formatDate(data.created_at) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto pr-10">
                                <div class="mb-1 text-xs text-gray-500">User ID</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="">{{ data.causer ? data.causer.id : '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto pr-10">
                                <div class="mb-1 text-xs text-gray-500">User</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="">{{ data.causer ? data.causer.name : '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto pr-10">
                                <div class="mb-1 text-xs text-gray-500">Description</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="">{{ data.description }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { defineProps } from 'vue';
import moment from 'moment';

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});

const formatDate = (date) => {
    return moment(date).format('DD-MM-YYYY hh:mm:ss A');
};

</script>
