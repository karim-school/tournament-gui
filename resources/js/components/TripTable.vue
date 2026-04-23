<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { TripRecord } from '@/types';

const props = defineProps<{
    trips: TripRecord[]
}>()

const mutableTrips: TripRecord[] = props.trips.map(trip => ({ ...trip, id: BigInt(trip.id) }));
</script>

<template>
    <div>
        <table class="table-auto w-full">
            <thead>
            <tr>
                <th class="text-left">ID</th>
                <th class="text-left">Type</th>
                <th class="text-left">Start Time</th>
                <th class="text-left">End Time</th>
                <th class="text-left">Start Station</th>
                <th class="text-left">End Station</th>
                <th class="text-left">Start Location</th>
                <th class="text-left">End Location</th>
                <th class="text-left">Member Casual</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(trip, index) in mutableTrips.sort((t1, t2) => t2.points - t1.points)" :key="index">
                <td><Link :href="'/trips/' + trip.id.toString(16)">{{ trip.id.toString(16).toUpperCase() }}</Link></td>
                <td>{{ trip.rideable_type }}</td>
                <td>{{ new Date(trip.started_at * 1000).toISOString() }}</td>
                <td>{{ new Date(trip.ended_at * 1000).toISOString() }}</td>
                <td>{{ trip.start_station.name }}</td>
                <td>{{ trip.end_station.name }}</td>
                <td>{{ trip.start_location.latitude.toFixed(2) + ", " + trip.start_location.longitude.toFixed(2) }}</td>
                <td>{{ trip.end_location.latitude.toFixed(2) + ", " + trip.end_location.longitude.toFixed(2) }}</td>
                <td>{{ trip.member_casual }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
