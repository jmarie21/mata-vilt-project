<script setup>
import Button from 'primevue/button';
import TextInput from './Components/TextInput.vue';

import { useForm } from '@inertiajs/vue3';
;

const form = useForm({
    password: '',  // Adjusted to match backend field
    password_confirmation: '',
});

const changePassword = () => {
    form.put(route('changePassword.update'), {
        onSuccess: () => {
            form.reset()
        },
        onError: (errors) => {
            console.error('Failed to change password:', errors);
        }
    });
}
</script>

<template>
    <div class="w-full h-screen flex items-center justify-center">
        <div class="h-auto w-5/6 md:w-1/3 flex flex-col mt-10 p-6 border border-gray-300 rounded-lg shadow-md">
            <h2 class="text-center text-3xl font-semibold mb-4">CHANGE PASSWORD</h2>
            <p class="text-center text-gray-600 mb-4">
                For your security purposes, please change your password before proceeding.
            </p>
            <form @submit.prevent="changePassword">
                <div class="mt-6">
                    <p class="mb-2">New Password</p>
                    <TextInput class="w-full" v-model="form.password" :message="form.errors.password" type="password" />
                    <p class="mb-2 mt-2">Confirm New Password</p>
                    <TextInput class="w-full" v-model="form.password_confirmation" :message="form.errors.password_confirmation" type="password" />
                </div>
                <div class="mt-6">
                    <Button label="Save" type="submit" class="w-full" />
                </div>
            </form>
        </div>
    </div>
</template>