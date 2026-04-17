<script setup lang="ts">
import UserList from '@/components/UserList.vue';
import { RankUser } from '@/types';
import { useForm, router, Form } from '@inertiajs/vue3';

const addUserForm = useForm({
    name: null,
});

defineProps<{
    users: RankUser[]
}>();

/*
let users = [];
router.get('/users', undefined, {
    onSuccess: (page) => {
        console.log(page);
    }
});
*/
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold text-primary-blue text-center">Rank List</h1>
        <h2 class="text-xl font-semibold text-primary-blue text-center">Subtitle</h2>
        <div class="text-center">
            <form @submit.prevent="addUserForm.post('/users', { onSuccess: () => addUserForm.reset() })" class="inline w-fit">
                <input type="text" v-model="addUserForm.name" name="name" autocomplete="off" class="outline">
                <button type="submit" :disabled="addUserForm.processing" class="ml-2">Tilføj</button>
            </form>
            <button v-on:click="router.reload({ only: [ 'users' ] })" class="ml-2 bg-primary-blue">Opdater</button>
        </div>
        <UserList :users="users" class="w-1/2 m-auto"></UserList>
    </div>
</template>
