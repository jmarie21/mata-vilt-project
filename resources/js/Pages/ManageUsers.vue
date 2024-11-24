<script setup>
import Divider from 'primevue/divider';
import MenubarLayout from '../Layouts/MenubarLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import TextInput from './Components/TextInput.vue';
import Select from 'primevue/select';
import Toast from 'primevue/toast';
import ConfirmDialog from 'primevue/confirmdialog';

import { ref } from "vue";
import { useForm } from '@inertiajs/vue3';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

const confirm = useConfirm();
const toast = useToast();
const editModal = ref(false);
const addNewUserModal = ref(false);

const roles = ref([
    { name: 'admin' },
    { name: 'user' },
    
]);

const props = defineProps({
    user: [Array, Object],
});

const editForm = useForm({
    name: props.user.name,
    email: props.user.email,
    role: '',
});

const addNewUserForm = useForm({
    name: null,
    email: null,
    role: null,
});

const openAddNewUserModal = () => {
    addNewUserModal.value = true;
};

const addNewUser = () => {
    addNewUserForm.post(route('users.add'), {
        onSuccess: () => {
            addNewUserModal.value = false;
            toast.add({ severity: 'success', summary: 'Added', detail: 'User added successfully!', life: 3000 });
            addNewUserForm.reset();
        },
        onError: () => addNewUserForm.reset('password', 'password_confirmation')
    });
};

const editUser = (user) => {
    editForm.id = user.id;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.role = user.role;
    editModal.value = true;
};

const saveEditedUser = () => {
    editForm.put(route('users.update', editForm.id), {
        onSuccess: () => {
            editForm.reset();
            editModal.value = false;  
            toast.add({ severity: 'success', summary: 'Saved', detail: 'Saved successfully!', life: 3000 });
        },
        onError: (errors) => {
            console.log(errors);
        }
    });
};

const deleteUser = (id) => {
    editForm.delete(route('users.delete', id), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Deleted', detail: 'User deleted successfully!', life: 3000 });
            confirm.close();
        },
        onError: (errors) => {
            console.log(errors);
        }
    });
};

const showTemplate = (id) => {
    confirm.require({
        group: 'delete_template',
        header: 'Delete user',
        message: 'Are you sure you want to delete this user?',
        icon: 'pi pi-exclamation-circle',
        rejectProps: {
            label: 'Cancel',
            outlined: true,
            size: 'small',
            severity: 'secondary'
        },
        acceptProps: {
            label: 'Delete',
            size: 'small',
            severity: 'danger'
        },
        accept: () => {
            deleteUser(id);
        },
        reject: () => {
            
        }
    });
};

defineOptions({
    layout: MenubarLayout
});
</script>

<template>
    <Head title="Manage Users" />
    <Toast />
    <div class="w-full flex items-center justify-center">
        <div class="h-auto w-5/6 flex flex-col mt-10">
            <div class="flex items-center justify-between">
                <h1 class="text-4xl">MANAGE USERS</h1>
                <Button label="Add user" icon="pi pi-plus" class="mr-4" severity="success" @click="openAddNewUserModal" />
                <Dialog v-model:visible="addNewUserModal" modal header="Add new user" :style="{ width: '500px' }">
                    <form @submit.prevent="addNewUser">
                        <div class="flex flex-col gap-4 mb-2">
                            <TextInput id="name" v-model="addNewUserForm.name" class="flex-auto" autocomplete="off" :message="addNewUserForm.errors.name" placeholder="Name" />
                            <TextInput id="email" v-model="addNewUserForm.email" class="flex-auto" autocomplete="off" :message="addNewUserForm.errors.email" placeholder="Email" />
                            <Select v-model="addNewUserForm.role" :options="roles" optionLabel="name" optionValue="name" placeholder="Select Role" class="w-full mb-4" />
                        </div>
                        <div class="flex justify-end gap-2">
                            <Button type="button" label="Cancel" severity="secondary" @click="addNewUserModal = false"></Button>
                            <Button type="submit" label="Add"></Button>
                        </div>
                    </form>
                </Dialog>
            </div>
            <Divider />
            <div class="card">
                <DataTable :value="user" tableStyle="min-width: 50rem">
                    <Column field="name" header="Name"></Column>
                    <Column field="email" header="Email"></Column>
                    <Column field="role" header="Role"></Column>
                    <Column header="Action">
                        <template #body="{ data }">
                            <Button size="small" label="Edit" icon="pi pi-pencil" class="mr-4" severity="info" @click="editUser(data)" />
                            <Dialog v-model:visible="editModal" modal header="Edit User" :style="{ width: '500px' }">
                                <form @submit.prevent="saveEditedUser">
                                    <div class="flex flex-col gap-4 mb-2">
                                        <TextInput v-model="editForm.name" class="flex-auto" autocomplete="off" placeholder="Name" />
                                        <TextInput v-model="editForm.email" class="flex-auto" autocomplete="off" placeholder="Email" />
                                        <Select v-model="editForm.role" :options="roles" optionLabel="name" optionValue="name" placeholder="Select Role" class="w-full mb-4" />
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <Button type="button" label="Cancel" severity="secondary" @click="editModal = false"></Button>
                                        <Button type="submit" label="Save"></Button>
                                    </div>
                                </form>
                            </Dialog>
                            <Button size="small" label="Delete" icon="pi pi-trash" severity="danger" @click="showTemplate(data.id)" />
                        </template>
                    </Column>
                </DataTable>
                <ConfirmDialog group="delete_template">
                    <template #message="slotProps">
                        <div class="flex flex-row items-center w-full gap-4 border-b border-surface-200 dark:border-surface-700">
                            <i :class="slotProps.message.icon" class="!text-3xl text-primary-500" style="color: red"></i>
                            <p>{{ slotProps.message.message }}</p>
                        </div>
                    </template>
                </ConfirmDialog>
            </div>
        </div>
    </div>
</template>

<Video />