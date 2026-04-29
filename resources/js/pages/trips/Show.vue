<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { TripRecord } from '@/types';

const props = defineProps<{
    trip: TripRecord;
}>();

const formatId = (id: string): string => {
    return BigInt(id).toString(16).toUpperCase();
};

const formatDate = (timestamp: number | string): string => {
    const date = new Date(timestamp);
    return date.toLocaleDateString('da-DK', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const pluralizeIfMultiple = (word: string, value: number): string => {
    return `${word}${value !== 1 ? 's' : ''}`;
}

const formatDuration = (start: number | string, end: number | string): string => {
    const startDate = new Date(start);
    const endDate = new Date(end);
    const diffMs = endDate.getTime() - startDate.getTime();
    const minutes = Math.floor(diffMs / 60000);
    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;
    
    if (hours === 0) {
        return `${minutes} ${pluralizeIfMultiple('minute', minutes)}`;
    }

    if (remainingMinutes === 0) {
        return `${hours} ${pluralizeIfMultiple('hour', hours)}`;
    }
    
    return `${hours} ${pluralizeIfMultiple('hour', hours)} ${remainingMinutes} ${pluralizeIfMultiple('minute', remainingMinutes)}`;
};

const formatCoordinates = (lat: number, lng: number): string => {
    return `${lat.toFixed(2)}\u00b0 N, ${lng.toFixed(2)}\u00b0 W`;
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <div class="flex items-center justify-between flex-wrap gap-y-4">
                    <div>
                        <Link
                            href="/trips"
                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                        >
                            ← Back to Trips
                        </Link>
                        <h1 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            Trip Details
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                            ID: {{ formatId(trip.id) }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Link
                            :href="`/trips/${trip.id}/edit`"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit
                        </Link>
                        <form
                            :action="`/trips/${trip.id}`"
                            method="POST"
                            @submit.prevent="confirm('Are you sure you want to delete this trip?') && $el.submit()"
                        >
                            <input type="hidden" name="_method" value="DELETE" />
                            <input type="hidden" name="_token" :value="($page as any).props.csrf_token" />
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Overview
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex space-x-4 gap-y-4 flex-wrap">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400 mr-3">Type:</span>
                            <span
                                :class="trip.rideable_type === 'electric_bike' ? 'px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'"
                            >
                                {{ trip.rideable_type === 'electric_bike' ? 'E-Bike' : 'Bike' }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400 mr-3">Rider:</span>
                            <span
                                :class="trip.member_casual === 'member' ? 'px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'"
                            >
                                {{ trip.member_casual === 'member' ? 'Member' : 'Casual' }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400 mr-3">Duration:</span>
                            <span class="text-sm">{{ formatDuration(trip.started_at, trip.ended_at) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex flex-wrap gap-x-4">
                        <span class="whitespace-nowrap">{{ formatDate(trip.started_at) }}</span>
                        <span class="whitespace-nowrap">-></span>
                        <span class="whitespace-nowrap">{{ formatDate(trip.ended_at) }}</span>
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Station</p>
                            <p v-if="trip.start_station.name !== trip.end_station.name" class="mt-1 text-lg text-gray-900 dark:text-white flex flex-wrap gap-x-4">
                                <span class="whitespace-nowrap">{{ trip.start_station?.name || 'Unknown' }}</span>
                                <span class="whitespace-nowrap">-></span>
                                <span class="whitespace-nowrap">{{ trip.end_station?.name || 'Unknown' }}</span>
                            </p>
                            <p v-else class="mt-1 text-lg text-gray-900 dark:text-white">
                                <span class="whitespace-nowrap">{{ trip.start_station?.name || 'Unknown' }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Location</p>
                            <p v-if="trip.start_station.name !== trip.end_station.name" class="mt-1 text-lg text-gray-900 dark:text-white flex flex-wrap gap-x-4">
                                <span class="whitespace-nowrap">{{ trip.start_station.location ? formatCoordinates(trip.start_station.location.latitude, trip.start_station.location.longitude) : 'N/A' }}</span>
                                <span class="whitespace-nowrap">-></span>
                                <span class="whitespace-nowrap">{{ trip.end_station.location ? formatCoordinates(trip.end_station.location.latitude, trip.end_station.location.longitude) : 'N/A' }}</span>
                            </p>
                            <p v-else class="mt-1 text-lg text-gray-900 dark:text-white">
                                <span class="whitespace-nowrap">{{ trip.start_station.location ? formatCoordinates(trip.start_station.location.latitude, trip.start_station.location.longitude) : 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
