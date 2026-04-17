<script setup lang="ts">
import { RankUser } from '@/types';
import { Link } from '@inertiajs/vue3';

defineProps<{
    users: RankUser[]
}>()
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
                <tr v-for="user in users.sort((u1, u2) => u2.points - u1.points)">
                    <td><Link :href="'/users/' + user.id">{{ user.name }}</Link></td>
                    <td>{{ user.points }}</td>
                    <td>{{ (user.win_percentage * 100).toFixed(2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
