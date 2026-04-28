<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import type { Station } from '@/types';
import moment from 'moment';

defineProps<{
    stations: Station[];
}>();

const currentTime = moment(Date.now()).format('YYYY-MM-DD HH:mm');

const form = useForm({
    rideable_type: 'electric_bike',
    started_at: currentTime,
    ended_at: currentTime,
    start_station_id: '',
    end_station_id: '',
    member_casual: 'member',
});

const submit = () => {
    form.post('/trips', {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <Link href="/trips" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                    ← Back to Trips
                </Link>
                <h1 class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">
                    Add New Trip
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Create a new trip record.
                </p>
            </div>

            <form @submit.prevent="submit" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ride Type *
                        </label>
                        <select
                            v-model="form.rideable_type"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.rideable_type }"
                        >
                            <option value="electric_bike">Electric Bike</option>
                            <option value="classic_bike">Classic Bike</option>
                        </select>
                        <p v-if="form.errors.rideable_type" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.rideable_type }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Rider Type *
                        </label>
                        <select
                            v-model="form.member_casual"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.member_casual }"
                        >
                            <option value="member">Member</option>
                            <option value="casual">Casual</option>
                        </select>
                        <p v-if="form.errors.member_casual" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.member_casual }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Date/Time *
                        </label>
                        <input
                            v-model="form.started_at"
                            type="datetime-local"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.started_at }"
                        />
                        <p v-if="form.errors.started_at" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.started_at }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Date/Time *
                        </label>
                        <input
                            v-model="form.ended_at"
                            type="datetime-local"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.ended_at }"
                        />
                        <p v-if="form.errors.ended_at" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.ended_at }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Station *
                        </label>
                        <select
                            v-model="form.start_station_id"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.start_station_id }"
                        >
                            <option value="">Select Station</option>
                            <option v-for="station in stations" :key="station.id" :value="station.id">
                                {{ station.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.start_station_id" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.start_station_id }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Station *
                        </label>
                        <select
                            v-model="form.end_station_id"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.end_station_id }"
                        >
                            <option value="">Select Station</option>
                            <option v-for="station in stations" :key="station.id" :value="station.id">
                                {{ station.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.end_station_id" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.end_station_id }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <Link
                        href="/trips"
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
                    >
                        {{ form.processing ? 'Creating...' : 'Create Trip' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
