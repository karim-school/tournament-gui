<script setup lang="ts">
import { RankUser } from '@/types';

defineProps<{
    users: RankUser[]
}>()
</script>

<template>
    <table>
        <thead>
            <tr>
                <th>Navn</th>
                <th>Points</th>
                <th>Win%</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in users">
                <td>{{ user.name }}</td>
                <td>{{ user.points }}</td>
                <td>{{ user.win_percentage }}</td>
            </tr>
        </tbody>
    </table>
</template>
