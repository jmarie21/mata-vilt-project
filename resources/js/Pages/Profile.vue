<script setup>
import MenubarLayout from '../Layouts/MenubarLayout.vue';
import Divider from 'primevue/divider';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import Avatar from 'primevue/avatar';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import TextInput from './Components/TextInput.vue';

import { useToast } from "primevue/usetoast";
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';


const value = ref(null);
const toast = useToast();

const props = defineProps({
    user: Object
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    old_password: '',
    new_password: '',
    new_password_confirmation: '',
});

const updatePersonalInformation = () => {
    form.put(route('personalInfo.update'), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Saved', detail: 'Personal Information updated successfully', life: 3000 });
        },
        onError: (errors) => {
           alert('Failed to update profile', errors);
        }
    });
}

const updatePassword = () => {
    form.put(route('update.password'), {
        onSuccess: () => {
            form.reset();
            toast.add({ severity: 'success', summary: 'Saved', detail: 'Password updated successfully', life: 3000 });
        },
        onError: (errors) => {
            console.log(errors);
            form.reset();
        }
    });
}

defineOptions({
    layout: MenubarLayout

});
</script>

<template>
    <Head :title="`${$page.component}`" />
    <div class="w-full flex items-center justify-center">
        <div class="h-auto w-5/6 flex flex-col mt-10">
            <h1 class="text-5xl">Profile</h1>
            <Divider />
            <div class="card">
                <Toast />
                <Tabs value="0">
                    <TabList>
                        <Tab value="0">Personal Information</Tab>
                        <Tab value="1">Security</Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel value="0">
                            <form @submit.prevent="updatePersonalInformation">
                                <div class="flex items-center space-x-4 mb-4 mt-6">
                                    <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png" size="xlarge" shape="circle" />
                                    <!-- <FileUpload ref="fileupload" mode="basic" name="demo[]" url="/api/upload" accept="image/*" :maxFileSize="1000000"/> -->
                                    <!-- <Button label="Upload" @click="upload" severity="secondary" /> -->
                                </div>
                                
                                <p class="mb-2 mt-2">Full Name</p>
                                <TextInput class="w-1/4" v-model="form.name" :message="form.errors.name" type="text" />
                                <p class="mb-2 mt-2">Email</p>
                                <TextInput class="w-1/4" v-model="form.email" :message="form.errors.email" type="text" />
                                

                                <div class="mt-6">
                                    <Button label="Save" type="submit" />
                                </div>
                            </form>
                            
                        </TabPanel>
                        <TabPanel value="1">
                            <form @submit.prevent="updatePassword">
                                    <div class="mt-6">
                                        <p class="mb-2 mt-2">Old Password</p>
                                        <TextInput class="w-1/4" v-model="form.old_password" :message="form.errors.old_password" type="password" />
                                        <p class="mb-2 mt-2">New Password</p>
                                        <TextInput class="w-1/4" v-model="form.new_password" :message="form.errors.new_password" type="password" />
                                        <p class="mb-2 mt-2">Confirm New Password</p>
                                        <TextInput class="w-1/4" v-model="form.new_password_confirmation" :message="form.errors.new_password_confirmation" type="password" />
                                    </div>

                                    <div class="mt-6">
                                        <Button label="Save" type="submit" />
                                    </div>
                            </form>
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
        </div>
    </div>
</template>