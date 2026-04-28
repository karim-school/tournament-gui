<script setup lang="ts">
import { ref, watch } from '@vue/runtime-core';
import { Link } from '@inertiajs/vue3';
import TripFilterBar from '@/components/TripFilterBar.vue';
import TripTable from '@/components/TripTable.vue';
import type { TripRecord } from '@/types';

const props = defineProps<{
    trips: TripRecord[];
    hasMore: boolean;
    currentPage: number;
    totalCount: number;
    filters: {
        rideable_type: string;
        member_casual: string;
        station: string;
        date_from: string;
        date_to: string;
        min_duration: string;
    };
}>();

const allTrips = ref<TripRecord[]>([...props.trips]);
const hasMoreData = ref(props.hasMore);
const currentPageData = ref(props.currentPage);
const isLoadingMore = ref(false);

watch(() => props.trips, (newTrips) => {
    if (props.currentPage === 1) {
        allTrips.value = [...newTrips];
        hasMoreData.value = props.hasMore;
        currentPageData.value = 1;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}, { deep: true });

const loadMore = async () => {
    if (isLoadingMore.value || !hasMoreData.value) return;
    isLoadingMore.value = true;
    currentPageData.value++;
    const activeFilters = Object.fromEntries(
        Object.entries(props.filters).filter(([_, v]) => v && v !== '')
    );
    const params = new URLSearchParams({
        ...activeFilters,
        api: 'true',
        page: currentPageData.value.toString(),
    }).toString();
    const response = await fetch(`/trips?${params}`);
    const data = await response.json();
    allTrips.value = [...allTrips.value, ...data.trips];
    hasMoreData.value = data.hasMore;
    isLoadingMore.value = false;
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <div class="flex items-center justify-between flex-wrap gap-y-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            TripTrack
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Browse and filter trip records. Showing {{ totalCount }} total records.
                        </p>
                    </div>
                    <Link
                        href="/trips/create"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add New Trip
                    </Link>
                </div>
            </div>

            <TripFilterBar :filters="filters" />

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <TripTable
                    :trips="allTrips"
                    :has-more="hasMoreData"
                    :current-page="currentPageData"
                    @load:more="loadMore"
                />
            </div>
        </div>
    </div>
</template>
