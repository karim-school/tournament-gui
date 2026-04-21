export * from './auth';

export type RankUser = {
    id: number;
    name: string;
    points: number;
    win_percentage: number;
};

export type WorldLocation = {
    latitude: number;
    longitude: number;
};

export type Station = {
    id: string; // e.g., 4368.05
    name: string; // e.g., Monroe St & Bedford Ave
};

export type TripRecord = {
    ride_id: string; // e.g., A324B30D9C1F6CFE
    rideable_type: string; // e.g., electric_bike
    started_at: string; // e.g., 2026-03-11 18:50:05.658
    ended_at: string; // e.g., 2026-03-11 19:02:07.383
    start_station: Station;
    end_station: Station;
    start_location: WorldLocation;
    end_location: WorldLocation;
    member_casual: string; // e.g., member
}
