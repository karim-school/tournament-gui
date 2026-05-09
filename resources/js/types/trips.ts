import type { User } from '@/types/auth';

export type WorldLocation = {
    latitude: number;
    longitude: number;
};

export enum RideType {
    CLASSIC_BIKE = 'classic_bike',
    ELECTRIC_BIKE = 'electric_bike',
}

export type Station = {
    id: number;
    name: string;
    location: WorldLocation;
};

export type TripRecord = {
    id: string;
    user: User;
    start_station: Station;
    end_station: Station;
    started_at: number;
    ended_at: number;
    ride_type: RideType;
}
