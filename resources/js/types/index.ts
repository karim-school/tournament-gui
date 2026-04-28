export * from './auth';

export type WorldLocation = {
    latitude: number;
    longitude: number;
};

export type Station = {
    id: number; // e.g., 4368
    sub_id: number; // e.g., 5
    name: string; // e.g., Monroe St & Bedford Ave
    location: WorldLocation;
};

export type TripRecord = {
    id: string; // e.g., A324B30D9C1F6CFE
    rideable_type: string; // e.g., electric_bike
    started_at: number; // e.g., 2026-03-11 18:50:05.658
    ended_at: number; // e.g., 2026-03-11 19:02:07.383
    start_station: Station;
    end_station: Station;
    member_casual: string; // e.g., member
}
