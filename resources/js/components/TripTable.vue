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
let observer: IntersectionObserver | null = null;

const setupObserver = () => {
    if (!sentinel.value) return;
    
    observer = new IntersectionObserver(
        (entries) => {
            const entry = entries[0];
            if (entry.isIntersecting) {
                emit('load:more');
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

const formatDateOnly = (timestamp: number | string): string => {
    const date = new Date(timestamp);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};

const formatDuration = (start: number, end: number): string => {
    const diff = new Date(end).getTime() - new Date(start).getTime();
    const minutes = Math.floor(diff / 60000);
    if (minutes < 60) {
        return `${minutes}m`;
    }
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours}h ${mins}m`;
};

const formatId = (id: string): string => {
    return BigInt(id).toString(16).toUpperCase();
};
</script>

<template>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0">
                <tr>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" colspan="2">
                        ID
                    </th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" colspan="1">
                        Type
                    </th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" colspan="2">
                        Start Date
                    </th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" colspan="1">
                        Duration
                    </th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" colspan="2">
                        Start Stn
                    </th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" colspan="2">
                        End Stn
                    </th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" colspan="1">
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
                    <td class="px-2 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 truncate" colspan="2">
                        <Link :href="'/trips/' + formatId(trip.id)" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            {{ formatId(trip.id) }}
                        </Link>
                    </td>
                    <td class="px-2 py-3 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400" colspan="1">
                        <span
                            :class="trip.rideable_type === 'electric_bike' ? 'px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'"
                        >
                            {{ trip.rideable_type === 'electric_bike' ? 'E-Bike' : 'Bike' }}
                        </span>
                    </td>
                    <td class="px-2 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" colspan="2">
                        {{ formatDateOnly(trip.started_at) }}
                    </td>
                    <td class="px-2 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" colspan="1">
                        {{ formatDuration(trip.started_at, trip.ended_at) }}
                    </td>
                    <td class="px-2 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 truncate" colspan="2">
                        {{ trip.start_station?.name || 'Unknown' }}
                    </td>
                    <td class="px-2 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 truncate" colspan="2">
                        {{ trip.end_station?.name || 'Unknown' }}
                    </td>
                    <td class="px-2 py-3 whitespace-nowrap text-center text-sm" colspan="1">
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
