<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import type { TripRecord } from '@/types';

defineProps<{
    trips: TripRecord[];
    hasMore: boolean;
    currentPage: number;
}>();

const emit = defineEmits<{
    (e: 'load:more'): void;
}>();

const sentinel = ref<HTMLElement | null>(null);
const isLoading = ref(false);
let observer: IntersectionObserver | null = null;

const setupObserver = () => {
    if (!sentinel.value) return;
    
    observer = new IntersectionObserver(
        (entries) => {
            const entry = entries[0];
            if (entry.isIntersecting && !isLoading.value) {
                isLoading.value = true;
                emit('load:more');
                setTimeout(() => {
                    isLoading.value = false;
                }, 500);
            }
        },
        { rootMargin: '100px' }
    );
    
    observer.observe(sentinel.value);
};

onMounted(() => {
    setupObserver();
});

onUnmounted(() => {
    observer?.disconnect();
});

watch(() => sentinel.value, () => {
    observer?.disconnect();
    setupObserver();
});

const formatDate = (timestamp: number | string): string => {
    const date = new Date(timestamp);

    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatId = (id: bigint | number): string => {
    const numId = typeof id === 'bigint' ? Number(id) : id;

    return numId.toString(16).toUpperCase();
};
</script>

<template>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Type
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Start Time
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        End Time
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Start Station
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        End Station
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Location
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Rider
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                <tr
                    v-for="(trip, index) in trips"
                    :key="index"
                    class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                        <Link :href="'/trips/' + formatId(trip.id)" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            {{ formatId(trip.id) }}
                        </Link>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <span
                            :class="trip.rideable_type === 'electric_bike' ? 'px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'"
                        >
                            {{ trip.rideable_type === 'electric_bike' ? 'Electric' : 'Classic' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ formatDate(trip.started_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ formatDate(trip.ended_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                        {{ trip.start_station?.name || 'Unknown' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                        {{ trip.end_station?.name || 'Unknown' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ trip.start_location?.latitude?.toFixed(2) }}, {{ trip.start_location?.longitude?.toFixed(2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span
                            :class="trip.member_casual === 'member' ? 'px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'"
                        >
                            {{ trip.member_casual === 'member' ? 'Member' : 'Casual' }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

        <div ref="sentinel" class="h-4"></div>

        <div v-if="!hasMore && trips.length > 0" class="p-4 text-center text-gray-500 dark:text-gray-400 text-sm">
            No more records to load
        </div>
    </div>
</template>