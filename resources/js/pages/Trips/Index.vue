<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
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
    };
}>();

const loadMore = () => {
    const nextPage = props.currentPage + 1;
    const query = { ...props.filters, page: nextPage };
    router.get('/trips', query, {
        preserveState: true,
        replace: true,
        onSuccess: () => {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        },
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    TripTrack
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Browse and filter trip records. Showing {{ totalCount }} total records.
                </p>
            </div>

            <TripFilterBar :filters="filters" />

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <TripTable
                    :trips="trips"
                    :has-more="hasMore"
                    :current-page="currentPage"
                    @load:more="loadMore"
                />
            </div>

            <div class="mt-4 flex justify-between items-center">
                <Link
                    href="/trips/create"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add New Trip
                </Link>
            </div>
        </div>
    </div>
</template>