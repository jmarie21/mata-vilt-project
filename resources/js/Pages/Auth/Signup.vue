<script setup>
import Card from 'primevue/card';
import Button from 'primevue/button';
import Divider from 'primevue/divider';
import TextInput from '../Components/TextInput.vue';

import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
});

const submit = () => {
    form.post(route('signup'), {
        onError: () => form.reset('password', 'password_confirmation')
    });
}
</script>

<template>
    <Head title="Signup" />
    <div class="h-screen flex items-center justify-center">
        <Card style="width: 28rem; overflow: hidden; border: 0.5px solid lightgray ">
            <template #header>
                <h1 class="text-center text-5xl text-bold mt-5">SIGNUP</h1>
            </template>
            <template #content>
                <form @submit.prevent="submit">
                    <div class="flex flex-col">
                        <TextInput placeholder="Name" v-model="form.name" :message="form.errors.name" />
                        <TextInput placeholder="Email" v-model="form.email" :message="form.errors.email" />
                        <TextInput type="password" placeholder="Password" v-model="form.password" :message="form.errors.password" />
                        <TextInput type="password" placeholder="Confirm Password" v-model="form.password_confirmation" />
                        <Button type="submit" label="Signup" class="w-full"  />
                        <Divider />
                        <p>Already have an account? <Link class="text-green-500" :href="route('login')">Login</Link></p>
                        <p>Go back to <Link class="text-green-500" :href="route('home')">Home</Link></p>
                    </div>
                </form>
            </template>
            
                
            
        </Card>
    </div>
</template>