<script setup>
import Divider from "primevue/divider";
import MenubarLayout from "../Layouts/MenubarLayout.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";

const props = defineProps({
    assignees: Object,
});

console.log(props.assignees);

defineOptions({
    layout: MenubarLayout,
});
</script>

<template>
    <Head :title="`${$page.component}`" />
    <div class="w-full flex items-center justify-center">
        <div class="h-auto w-full flex flex-col mt-10 px-16">
            <div class="flex flex-col items-start mb-6">
                <h1 class="text-4xl">Reports</h1>
                <Divider class="w-full mt-4" />

                <div class="card w-full">
                    <DataTable
                        :value="Object.entries(assignees)"
                        tableStyle="min-width: 50rem"
                        :paginator="true"
                        :rows="10"
                    >
                        <Column field="0" header="Assignee"></Column>
                        <Column field="1" header="Total Time Spent"></Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </div>
</template>
