import type { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx } from 'clsx';
import type { ClassValue } from 'clsx';
import type { Moment } from 'moment';
import { twMerge } from 'tailwind-merge';
import { Membership, RideType } from '@/types';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function formatMembership(membership: Membership): string {
    switch (membership) {
        case Membership.GUEST: return 'Casual';
        case Membership.MEMBER: return 'Member';
        default: return 'Unknown';
    }
}

export function membershipClasses(membership: Membership): string {
    switch (membership) {
        case Membership.MEMBER:
            return 'px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200';
        case Membership.GUEST:
        default:
            return 'px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
    }
}

export function formatRideType(rideType: RideType): string {
    switch (rideType) {
        case RideType.CLASSIC_BIKE: return 'Bike';
        case RideType.ELECTRIC_BIKE: return 'E-Bike';
        default: return 'Unknown';
    }
}

export function rideTypeClasses(rideType: RideType): string {
    switch (rideType) {
        case RideType.CLASSIC_BIKE:
            return 'px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        case RideType.ELECTRIC_BIKE:
        default:
            return 'px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
    }
}

export function toISODateTimeByMinute(moment: Moment): string {
    return moment.utc(false).format('YYYY-MM-DD\\THH:mm:00\\Z');
}
