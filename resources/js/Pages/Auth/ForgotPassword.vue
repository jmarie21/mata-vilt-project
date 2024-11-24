<script setup>
import Card from 'primevue/card';
import Button from 'primevue/button';
import TextInput from '../Components/TextInput.vue';
import Toast from 'primevue/toast';
import { useForm } from '@inertiajs/vue3';
import { Head, Link } from '@inertiajs/vue3';
import { useToast } from "primevue/usetoast";

const toast = useToast();

const form = useForm({
    email: null,
});

const submit = () => {
    form.post(route('password.email'), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Sent', detail: 'Email sent, please check your email to reset your password', life: 3000 });
        },
        onError: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Forgot Password" />
    <Toast />
    <div class="h-screen flex items-center justify-center">
        <Card style="width: 28rem; overflow: hidden; border: 0.5px solid lightgray">
            <template #header>
                <h1 class="text-center text-5xl text-bold mt-5">Forgot Password</h1>
            </template>
            <template #content>
                <form @submit.prevent="submit">
                    <div class="flex flex-col">
                        <TextInput placeholder="Email" v-model="form.email" :message="form.errors.email" />
                        <Button type="submit" label="Send Password Reset Link" class="w-full" />
                        <p class="mt-4">Remembered your password? <Link class="text-green-500" :href="route('login')">Login</Link></p>
                    </div>
                </form>
            </template>
        </Card>
    </div>
</template>
