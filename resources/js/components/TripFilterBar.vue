<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import TripController from '@/actions/App/Http/Controllers/TripController';
import { formatMembership } from '@/lib/utils';
import { Membership } from '@/types';

const props = defineProps<{
    filters: {
        ride_type: string;
        rider_type: string;
        //station: string;
        date_from: string;
        date_to: string;
        min_duration: string;
    };
}>();

const localFilters = ref({ ...props.filters });

watch(localFilters, () => {
    router.get(TripController.index(), localFilters.value, { preserveState: true });
}, { deep: true });

const resetFilters = () => {
    localFilters.value = {
        ride_type: 'all',
        rider_type: 'all',
        //station: '',
        date_from: '',
        date_to: '',
        min_duration: '',
    };
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 md:grid-rows-2 gap-4">
            <div>
                <label
                    for="filter-ride-type"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >
                    Ride Type
                </label>
                <select
                    id="filter-ride-type"
                    v-model="localFilters.ride_type"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="all">All Types</option>
                    <option value="electric_bike">Electric Bike</option>
                    <option value="classic_bike">Classic Bike</option>
                </select>
            </div>

            <div>
                <label
                    for="filter-rider-type"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >
                    Rider Type
                </label>
                <select
                    id="filter-rider-type"
                    v-model="localFilters.rider_type"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="all">All Riders</option>
                    <option v-for="membership in Membership"
                            :key="membership"
                            :value="membership"
                    >
                        {{ formatMembership(membership) }}
                    </option>
                </select>
            </div>

            <div v-if="false">
                <label
                    for="filter-station"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >
                    Station
                </label>
                <input
                    id="filter-station"
                    type="text"
                    v-model="localFilters.station"
                    placeholder="Search stations..."
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
            </div>

            <div>
                <label
                    for="filter-min-duration"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >
                    Min. Duration
                </label>
                <input
                    id="filter-min-duration"
                    type="number"
                    min="0"
                    v-model="localFilters.min_duration"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
            </div>

            <div>
                <label
                    for="filter-from-date"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >
                    From Date
                </label>
                <input
                    id="filter-from-date"
                    type="date"
                    v-model="localFilters.date_from"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
            </div>

            <div>
                <label
                    for="filter-to-date"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >
                    To Date
                </label>
                <input
                    id="filter-to-date"
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
