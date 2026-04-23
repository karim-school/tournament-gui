<script setup lang="ts">
import TripTable from '@/components/TripTable.vue';
import { TripRecord } from '@/types';

const props = defineProps<{
    trips: TripRecord[]
}>();
</script>

<template>
    <div>
        <h1>Test</h1>
        <TripTable :trips="props.trips" class="w-2/3 m-auto"></TripTable>
    </div>
</template>
