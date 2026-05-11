<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import moment from 'moment/moment';
import TripController from '@/actions/App/Http/Controllers/TripController';
import { toISODateTimeByMinute } from '@/lib/utils';
import type { RideType, Station, TripRecord, WorldLocation } from '@/types';
import { onMounted, toRef } from 'vue';
import { addDistanceMarkerEvents, useMap } from '@/composables/useMap';

const props = defineProps<{
    trip: TripRecord;
    stations: Station[];
}>();

type FormData = {
    ride_type: RideType;
    started_at: string;
    ended_at: string;
    start_location: WorldLocation;
    end_location: WorldLocation;
}

const form = useForm<FormData>({
    ride_type: props.trip.ride_type,
    started_at: moment(props.trip.started_at).local().format('YYYY-MM-DDTHH:mm'),
    ended_at: moment(props.trip.ended_at).local().format('YYYY-MM-DDTHH:mm'),
    start_location: props.trip.start_station.location,
    end_location: props.trip.end_station.location,
});

const submit = () => {
    form.transform((data: FormData) => ({
        ...data,
        started_at: toISODateTimeByMinute(moment(data.started_at)),
        ended_at: toISODateTimeByMinute(moment(data.ended_at)),
    })).put(TripController.update(props.trip.id), {
        onSuccess: () => form.reset(),
    });
};

onMounted(() => {
    const { getMap } = useMap();
    const map = getMap();
    const startLocation = toRef(form, 'start_location');
    const endLocation = toRef(form, 'end_location');
    const markers = Array(2);
    const line = L.polyline([[0, 0], [0, 0]], {color: 'red'});

    addDistanceMarkerEvents(map, startLocation, endLocation, toRef(markers), toRef(line));

    const start = L.latLng(props.trip.start_station.location.latitude, props.trip.start_station.location.longitude);
    const end = L.latLng(props.trip.end_station.location.latitude, props.trip.end_station.location.longitude);

    let options = {
        draggable: true,
    };

    markers[0] = L.marker(start, { ...options, title: 'Start' }).addTo(map);
    markers[1] = L.marker(end, { ...options, title: 'End' }).addTo(map);
    line.setLatLngs([start, end]).addTo(map);

    markers[0].on('click', () => {
        markers[0].remove();
        markers[0] = undefined;
        line.remove();
        form.start_location = null;
    });

    markers[0].on('move', (event) => {
        form.start_location = { latitude: event.latlng.lat, longitude: event.latlng.lng };

        if (markers[1]) {
            line.setLatLngs([event.latlng, markers[1].getLatLng()]);
        }
    });

    markers[1].on('click', () => {
        markers[1].remove();
        markers[1] = undefined;
        line.remove();
        form.end_location = null;
    });

    markers[1].on('move', (event) => {
        form.end_location = { latitude: event.latlng.lat, longitude: event.latlng.lng };

        if (markers[0]) {
            line.setLatLngs([markers[0].getLatLng(), event.latlng]);
        }
    });
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <Link
                    :href="TripController.show(trip.id)"
                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                >
                    ← Back to Trip
                </Link>
                <h1 class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">
                    Update Trip
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Update trip record with ID: {{ trip.id }}
                </p>
            </div>

            <form @submit.prevent="submit" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                            Ride Type *
                        </label>
                        <select
                            v-model="form.ride_type"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.ride_type }"
                        >
                            <option value="electric_bike">Electric Bike</option>
                            <option value="classic_bike">Classic Bike</option>
                        </select>
                        <p v-if="form.errors.ride_type" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.ride_type }}
                        </p>
                    </div>
                </div>

                <p v-if="form.errors.start_location" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ form.errors.start_location }}
                </p>

                <p v-if="form.errors.end_location" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ form.errors.end_location }}
                </p>

                <div id="map" style="height: 500px"></div>

                <div class="mt-6 flex justify-end gap-4">
                    <Link
                        :href="TripController.show(trip.id)"
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
                    >
                        {{ form.processing ? 'Updating...' : 'Update Trip' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
