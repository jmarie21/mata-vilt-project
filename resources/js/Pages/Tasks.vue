<script setup>
import MenubarLayout from '../Layouts/MenubarLayout.vue';
import Divider from 'primevue/divider';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';



defineProps({
    tasks: {
        type: Array,
        default: () => [],
    },
});




defineOptions({
    layout: MenubarLayout
});
</script>


<template>
    <Head :title="`${$page.component}`" />
    <div class="w-full flex items-center justify-center">
        <div class="h-auto w-5/6 flex flex-col mt-10">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-4xl">Clickup Tasks</h1>
                
            </div>
            <Divider /> 

            <!-- DataTable -->
             
            <div v-if="tasks && tasks.length > 0" class="card">
                <DataTable :value="tasks" tableStyle="min-width: 50rem">
                    <!-- Task Name -->
                    <Column field="name" header="Task Name"></Column>

                    <!-- Description -->
                    <Column field="description" header="Description"></Column>

                    <!-- Status -->
                    <Column field="status.status" header="Status"></Column>

                    <!-- Due Date -->
                    <Column field="creator.username" header="Created by"></Column>
                </DataTable>
            </div>
            <div v-else>
                <p>No tasks found.</p>
            </div>
        </div>
    </div>
</template>