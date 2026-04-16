<script setup lang="ts">
import UserList from '@/components/UserList.vue';
import { useForm, router } from '@inertiajs/vue3';

const addUserForm = useForm({
    name: null,
});

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
        <h1 class="text-2xl font-bold text-primary-blue">Rank List</h1>
        <h2 class="text-xl font-semibold text-primary-blue">Subtitle</h2>
        <form @submit.prevent="addUserForm.post('/users')">
            <input type="text" name="name" v-model="addUserForm.name">
            <button type="submit">Tilføj</button>
        </form>
    </div>
</template>
