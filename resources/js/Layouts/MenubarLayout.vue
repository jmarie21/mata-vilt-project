<script setup>
import Button from 'primevue/button';
import Menubar from 'primevue/menubar';
import Drawer from 'primevue/drawer';
import Avatar from 'primevue/avatar';
import Menu from 'primevue/menu';
import {router} from '@inertiajs/vue3';
import { ref } from "vue";

const optionsMenu = ref();
const items = ref([
    {
        label: 'Options',
        items: [
            {
                label: 'Profile',
                icon: 'pi pi-user',
                command: () => {
                    router.visit('/profile')
                }
            },
            {
                label: 'Logout',
                icon: 'pi pi-sign-out',
                command: () => {
                    router.post('logout')
                }
            }
        ]
    }
]);

const toggle = (event) => {
    optionsMenu.value.toggle(event);
};


const drawerMenu = ref(false);

const props = defineProps({
    can: {
        type: Object,
        default: () => ({})
    }
});
</script>


<template>
    <div class="card">
        <Menubar class="fixed top-0 left-0 w-full" >
            <template #start>
                <div v-if="$page.props.auth.user">
                    <Button class="!bg-white !text-black !border-white" :label="$page.component" icon="pi pi-bars" @click="drawerMenu = true" />
                    <Drawer v-model:visible="drawerMenu">
                        <template #header>
                            <div class="flex items-center gap-2 ">
                                <h1 class="font-bold">My App</h1>
                            </div>
                        </template>
                            <div v-if="can.admin_user" class="py-4">
                                <ul class="list-none p-0 m-0">
                                    <li class="mb-2 py-2 px-4 hover:bg-gray-200 cursor-pointer">
                                        <Link :href="route('dashboard')" @click="drawerMenu = false" class="flex items-center gap-2">
                                            <i class="pi pi-home"></i> Dashboard
                                        </Link>
                                    </li>
                                    <li class="mb-2 py-2 px-4 hover:bg-gray-200 cursor-pointer">
                                        <Link :href="route('reports')" @click="drawerMenu = false" class="flex items-center gap-2">
                                            <i class="pi pi-chart-line"></i> Reports
                                        </Link>
                                    </li>
                                    <li class="mb-2 py-2 px-4 hover:bg-gray-200 cursor-pointer">
                                        <Link :href="route('invoice')" @click="drawerMenu = false" class="flex items-center gap-2">
                                            <i class="pi pi-file"></i> Invoice
                                        </Link>
                                    </li>
                                    <li class="mb-2 py-2 px-4 hover:bg-gray-200 cursor-pointer">
                                        <Link :href="route('projects')" @click="drawerMenu = false" class="flex items-center gap-2">
                                            <i class="pi pi-briefcase"></i> Projects
                                        </Link>
                                    </li>
                                    <li class="mb-2 py-2 px-4 hover:bg-gray-200 cursor-pointer">
                                        <Link :href="route('manage_users')" @click="drawerMenu = false" class="flex items-center gap-2">
                                            <i class="pi pi-users"></i> Manage Users
                                        </Link>
                                    </li>
                                    <li class="mb-2 py-2 px-4 hover:bg-gray-200 cursor-pointer">
                                        <Link :href="route('time_sheets')" @click="drawerMenu = false" class="flex items-center gap-2">
                                            <i class="pi pi-calendar-clock"></i> Time Sheets
                                        </Link>
                                    </li>
                                    <li class="mb-2 py-2 px-4 hover:bg-gray-200 cursor-pointer">
                                        <Link :href="route('tasks.index')" @click="drawerMenu = false" class="flex items-center gap-2">
                                            <i class="pi pi-briefcase"></i> Tasks
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                            <div v-else>
                                <ul class="list-none p-0 m-0">
                                    <li class="mb-2 py-2 px-4 hover:bg-gray-200 cursor-pointer">
                                        <Link :href="route('dashboard')" @click="visible = false" class="flex items-center gap-2">
                                            <i class="pi pi-home"></i> Dashboard
                                        </Link>
                                    </li>
                                    <li class="mb-2 py-2 px-4 hover:bg-gray-200 cursor-pointer">
                                        <Link :href="route('projects')" @click="visible = false" class="flex items-center gap-2">
                                            <i class="pi pi-briefcase"></i> Projects
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                           
                        <template #footer>
                            <div class="flex items-center gap-2 ">
                                <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png" shape="circle" />
                                <span class="font-bold">{{ $page.props.auth.user.name }}</span>
                            </div>
                        </template>
                    </Drawer>
                </div>
                <div v-else>
                    <h1><Link :href="route('home')">My App</Link></h1>
                </div>
                
            </template>
            <template #end>
                <div v-if="$page.props.auth.user">
                    <Button severity="secondary" @click="toggle"><Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png" shape="circle" /></Button>
                    <Menu ref="optionsMenu" id="overlay_menu" :model="items" :popup="true" />
                </div>
                <div v-else>
                    <div class="flex items-center gap-2">
                        <Link :href="route('login')"><Button label="Login" /></Link>
                    </div>
                </div>
                
            </template>
        </Menubar>

        <main class="mt-16 overflow-auto">
            
            <slot />
        </main>
    </div>
</template>


