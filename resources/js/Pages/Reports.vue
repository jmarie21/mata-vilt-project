<script setup>
import Divider from "primevue/divider";
import MenubarLayout from "../Layouts/MenubarLayout.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";

defineOptions({
    layout: MenubarLayout,
});
</script>

<template>
    <Head :title="`${$page.component}`" />
    <div class="w-full flex items-center justify-center">
        <div class="h-auto w-5/6 flex flex-col mt-10">
            <div class="flex flex-col items-start mb-6">
                <h1 class="text-4xl">Reports</h1>
                <Divider class="w-full mt-4" />

                <div class="card">
                    <DataTable
                        :value="tasks"
                        tableStyle="min-width: 50rem"
                        :paginator="true"
                        :rows="10"
                    >
                        <Column field="name" header="User"></Column>
                        <Column field="description" header="Task Name"></Column>
                        <Column field="status" header="Status"></Column>
                        <Column field="time_spent" header="Time Spent"></Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </div>
</template>
