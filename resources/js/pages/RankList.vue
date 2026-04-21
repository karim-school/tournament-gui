<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import UserList from '@/components/UserList.vue';
import type { RankUser } from '@/types';

const addUserForm = useForm({
    name: null,
});

const addUser = () => {
    addUserForm.post('/users', {
        onSuccess: () => addUserForm.reset()
    });
};

defineProps<{
    users: RankUser[]
}>();
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold text-primary-blue text-center">Rank List</h1>
        <h2 class="text-xl font-semibold text-primary-blue text-center">Subtitle</h2>
        <div class="text-center">
            <form @submit.prevent="addUser" class="inline w-fit">
                <input type="text" v-model="addUserForm.name" name="name" autocomplete="off" class="outline">
                <button type="submit" :disabled="addUserForm.processing" class="ml-2">Tilføj</button>
            </form>
            <button v-on:click="router.reload({ only: [ 'users' ] })" class="ml-2 bg-primary-blue">Opdater</button>
        </div>
        <UserList :users="users" class="w-1/2 m-auto"></UserList>
    </div>
</template>
