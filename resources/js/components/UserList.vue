<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { RankUser } from '@/types';

const props = defineProps<{
    users: RankUser[]
}>()

const mutableUsers: RankUser[] = props.users;
</script>

<template>
    <div>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="text-left">Navn</th>
                    <th class="text-left">Points</th>
                    <th class="text-left">Win%</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in mutableUsers.sort((u1, u2) => u2.points - u1.points)" :key="index">
                    <td><Link :href="'/users/' + user.id">{{ user.name }}</Link></td>
                    <td>{{ user.points }}</td>
                    <td>{{ (user.win_percentage * 100).toFixed(2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
