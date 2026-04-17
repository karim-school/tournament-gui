<script setup lang="ts">
import { RankUser } from '@/types';
import { useForm } from '@inertiajs/vue3';

defineProps<{
    user: RankUser
}>();

const deleteUserForm = useForm();
</script>

<template>
    <div>
        <h1>{{ user.name }}</h1>
        <form @submit.prevent="deleteUserForm.delete('/users/' + user.id)">
            <button type="submit">Delete</button>
        </form>
    </div>
</template>
