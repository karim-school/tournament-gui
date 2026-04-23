<script setup lang="ts">
import { TripRecord } from '@/types';

const props = defineProps<{
    trip: TripRecord
}>();

const id = BigInt(props.trip.id);
</script>

<template>
    <div>
        <h1>Test</h1>
        <p>{{ id.toString(16).toUpperCase() }}</p>
    </div>
</template>
