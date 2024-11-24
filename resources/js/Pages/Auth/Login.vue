<script setup>
import Card from 'primevue/card';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import Divider from 'primevue/divider';
import TextInput from '../Components/TextInput.vue';

import { useForm } from '@inertiajs/vue3';


const form = useForm({
    email: null,
    password: null,
    remember: null,
});

const submit = () => {
    form.post(route('login'), {
        onError: () => form.reset('password')
    });
}

</script>

<template>
    <Head title="Login" />
    <div class="h-screen flex items-center justify-center">
        <Card style="width: 28rem; overflow: hidden; border: 0.5px solid lightgray ">
            <template #header>
                <h1 class="text-center text-5xl text-bold mt-5">LOGIN</h1>
            </template>
            <template #content>
                <form @submit.prevent="submit">
                    <div class="flex flex-col">
                        <TextInput placeholder="Email" v-model="form.email" :message="form.errors.email" />
                        <TextInput type="password" placeholder="Password" v-model="form.password" :message="form.errors.password" />

                        <div class="flex items-center mb-4 mt-4">
                            <Checkbox v-model="form.remember" inputId="ingredient1" name="remember" value="Remember" />
                            <label for="ingredient1" class="ml-2"> Remember me </label>
                        </div>

                        
                        
                        <Button type="submit" label="Login" class="w-full" />
                        <Divider />
                        <Link class="text-green-500 mb-2" :href="route('password.request')"> Forgot Password? </Link>
                        <p>Go back to <Link class="text-green-500" :href="route('home')">Home</Link></p>
                    </div>
                </form>
            </template>
        </Card>
    </div>
</template>