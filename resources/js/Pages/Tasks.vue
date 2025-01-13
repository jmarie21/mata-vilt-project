<script setup>
import MenubarLayout from "../Layouts/MenubarLayout.vue";
import Divider from "primevue/divider";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import { ref, onMounted, onBeforeUnmount } from "vue";
import { router } from "@inertiajs/vue3";

const toast = useToast();
const isLoading = ref(false);
const tasks = ref([]);

const props = defineProps({
    tasks: {
        type: Array,
        default: () => [],
    },
});

onMounted(() => {
    tasks.value = [...props.tasks]; // Initialize tasks with the prop value
    setupRealTimeUpdates();
});

// Existing fetchTasks function
const fetchTasks = () => {
    isLoading.value = true;

    router.post(
        route("tasks.fetch"),
        {},
        {
            onStart: () => {
                toast.add({
                    severity: "info",
                    summary: "Fetching Tasks",
                    detail: "Retrieving latest ClickUp tasks",
                    life: 2000,
                });
            },
            onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: "Success",
                    detail: "Tasks fetched successfully",
                    life: 3000,
                });
            },
            onError: (error) => {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: "Failed to fetch tasks",
                    life: 3000,
                });
                console.error("Error fetching tasks: ", error);
            },
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
};

// Listen for real-time task updates
const setupRealTimeUpdates = () => {
    Echo.private("tasks").listen("TasksFetched", (event) => {
        console.log("Real-time event received:", event);
        // toast.add({
        //     severity: "info",
        //     summary: "Real-Time Update",
        //     detail: "Tasks updated in real-time!",
        //     life: 3000,
        // });

        console.log("Tasks fetched via broadcast: ", event.tasks);
        tasks.value = event.tasks; // Update the tasks dynamically
    });
};

// Cleanup when the component is destroyed
onBeforeUnmount(() => {
    Echo.leave("tasks");
});

defineOptions({
    layout: MenubarLayout,
});
</script>

<template>
    <Head :title="`${$page.component}`" />
    <div class="w-full flex items-center justify-center">
        <div class="h-auto w-5/6 flex flex-col mt-10">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-4xl">Clickup Tasks</h1>
                <Button
                    @click="fetchTasks"
                    label="Fetch Tasks"
                    severity="success"
                    :loading="isLoading"
                />
            </div>
            <Divider />

            <div v-if="tasks && tasks.length > 0" class="card">
                <DataTable
                    :value="tasks"
                    tableStyle="min-width: 50rem"
                    :paginator="true"
                    :rows="10"
                >
                    <Column field="name" header="Task Name"></Column>
                    <Column field="description" header="Description"></Column>
                    <Column field="assignees" header="Assignees"></Column>
                    <Column field="time_spent" header="Time Spent"></Column>
                    <Column field="status" header="Status"></Column>
                    <Column field="creator" header="Created by"></Column>
                </DataTable>
            </div>
            <div v-else class="text-center p-4 bg-gray-100 rounded">
                <p class="text-gray-600">
                    No tasks found. Click "Fetch Tasks" to retrieve tasks.
                </p>
            </div>
        </div>

        <Toast />
    </div>
</template>
