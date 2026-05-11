import 'leaflet/dist/leaflet.css';
import * as L from 'leaflet';
import type { Ref } from 'vue';
import type { WorldLocation } from '@/types';

type MapStateOptions = {
    mapId: string,
    center: number[],
    zoom: number,
    minZoom: number,
    maxZoom: number,
};

const defaultOptions: MapStateOptions = {
    mapId: 'map',
    center: [55.399294, 10.385685],
    zoom: 12,
    minZoom: 3,
    maxZoom: 18,
};

export function getMap(options: MapStateOptions = defaultOptions): any {
    const map = L.map(options.mapId).setView(options.center, options.zoom);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        minZoom: options.minZoom,
        maxZoom: options.maxZoom,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    return map;
}

export function addDistanceMarkerEvents(
    map: any,
    start: Ref<WorldLocation>,
    end: Ref<WorldLocation>,
    markers: Ref<number[]>,
    line: Ref
): void {
    map.on('click', (event) => {
        if (markers.value[0] && markers.value[1]) {
            return;
        }

        const options = {
            draggable: true,
        };
        let marker = undefined;

        if (!markers.value[0]) {
            marker = L.marker(event.latlng, { ...options, title: 'Start' });
            markers.value[0] = marker;
            start.value = { latitude: event.latlng.lat, longitude: event.latlng.lng };

            if (markers.value[1]) {
                line.value.setLatLngs([event.latlng, markers.value[1].getLatLng()]);
                line.value.addTo(map);
            }

            marker.on('click', () => {
                markers.value[0] = undefined;
                marker.remove();
                line.value.remove();
                start.value = null;
            });

            marker.on('move', (event) => {
                start.value = { latitude: event.latlng.lat, longitude: event.latlng.lng };

                if (markers.value[1]) {
                    line.value.setLatLngs([event.latlng, markers.value[1].getLatLng()]);
                }
            });
        } else {
            marker = L.marker(event.latlng, { ...options, title: 'End' });
            markers.value[1] = marker;
            end.value = { latitude: event.latlng.lat, longitude: event.latlng.lng };

            if (markers.value[0]) {
                line.value.setLatLngs([markers.value[0].getLatLng(), event.latlng]);
                line.value.addTo(map);
            }

            marker.on('click', () => {
                markers.value[1] = undefined;
                marker.remove();
                line.value.remove();
                end.value = null;
            });

            marker.on('move', (event) => {
                end.value = { latitude: event.latlng.lat, longitude: event.latlng.lng };

                if (markers.value[0]) {
                    line.value.setLatLngs([markers.value[0].getLatLng(), event.latlng]);
                }
            });
        }

        marker.addTo(map);
    });
}

export function useMap() {
    return { getMap };
}
