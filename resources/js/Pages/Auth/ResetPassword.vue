<script setup>
import Card from 'primevue/card';
import Button from 'primevue/button';
import TextInput from '../Components/TextInput.vue';
import Toast from 'primevue/toast';
import { useForm } from '@inertiajs/vue3';
import { Head, Link } from '@inertiajs/vue3';
import { useToast } from "primevue/usetoast";


const toast = useToast();
const props = defineProps(['token']);

const form = useForm({
    email: null,
    password: null,
    password_confirmation: null,
    token: props.token, 
});

const submit = () => {
    form.post(route('password.update'), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Reset Password', detail: 'Password reset successfully', life: 3000 });
        },
        onError: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Reset Password" />
    <Toast />
    <div class="h-screen flex items-center justify-center">
        <Card style="width: 28rem; overflow: hidden; border: 0.5px solid lightgray">
            <template #header>
                <h1 class="text-center text-5xl text-bold mt-5">Reset Password</h1>
            </template>
            <template #content>
                <form @submit.prevent="submit">
                    <div class="flex flex-col">
                        <TextInput 
                            placeholder="Email" 
                            v-model="form.email" 
                            :message="form.errors.email" 
                        />
                        <TextInput 
                            type="password" 
                            placeholder="New Password" 
                            v-model="form.password" 
                            :message="form.errors.password" 
                        />
                        <TextInput 
                            type="password" 
                            placeholder="Confirm Password" 
                            v-model="form.password_confirmation" 
                            :message="form.errors.password_confirmation" 
                        />
                        
                        <Button type="submit" label="Reset Password" class="w-full" />
                        <p class="mt-4">Remembered your password? <Link class="text-green-500" :href="route('login')">Login</Link></p>
                    </div>
                </form>
            </template>
        </Card>
    </div>
</template>
