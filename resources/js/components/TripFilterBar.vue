<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
    filters: {
        rideable_type: string;
        member_casual: string;
        station: string;
        date_from: string;
        date_to: string;
    };
}>();

const localFilters = ref({ ...props.filters });

watch(localFilters, () => {
    router.get('/trips', localFilters.value, { preserveState: true });
}, { deep: true });

const resetFilters = () => {
    localFilters.value = {
        rideable_type: 'all',
        member_casual: 'all',
        station: '',
        date_from: '',
        date_to: '',
    };
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Ride Type
                </label>
                <select
                    v-model="localFilters.rideable_type"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="all">All Types</option>
                    <option value="electric_bike">Electric Bike</option>
                    <option value="classic_bike">Classic Bike</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Rider Type
                </label>
                <select
                    v-model="localFilters.member_casual"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="all">All Riders</option>
                    <option value="member">Member</option>
                    <option value="casual">Casual</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Station
                </label>
                <input
                    type="text"
                    v-model="localFilters.station"
                    placeholder="Search stations..."
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    From Date
                </label>
                <input
                    type="date"
                    v-model="localFilters.date_from"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    To Date
                </label>
                <input
                    type="date"
                    v-model="localFilters.date_to"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
            </div>
        </div>

        <div class="mt-4 flex justify-end">
            <button
                type="button"
                @click="resetFilters"
                class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
            >
                Reset Filters
            </button>
        </div>
    </div>
</template>