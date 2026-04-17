<script setup lang="ts">
import UserList from '@/components/UserList.vue';
import { RankUser } from '@/types';
import { useForm, router } from '@inertiajs/vue3';

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
        <form @submit.prevent="addUserForm.post('/users')" class="text-center">
            <input type="text" name="name" v-model="addUserForm.name">
            <button type="submit">Tilføj</button>
        </form>
        <UserList :users="users" class="w-1/2 m-auto"></UserList>
    </div>
</template>
