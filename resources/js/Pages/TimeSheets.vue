<script setup>
import MenubarLayout from "../Layouts/MenubarLayout.vue";
import Divider from "primevue/divider";
import Toast from "primevue/toast";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { ref, onMounted, onUnmounted } from "vue";

const toast = useToast();
const interval = ref(null);

const form = useForm({
    file: null,
});

const props = defineProps({
    timesheets: {
        type: Array,
        default: () => [],
    },
});

const timesheets = ref([...props.timesheets]);

const formatDate = (value) => {
    if (value) {
        const date = new Date(value);
        const year = date.getFullYear().toString().slice(-2);
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0");
        return `${year}-${day}-${month}`;
    }
    return "";
};

const formatDuration = (value) => {
    if (typeof value === "number") {
        const hours = Math.floor(value / 60);
        const minutes = value % 60;
        return `${hours}h ${minutes}m`;
    }

    return "0h 0m";
};

const fetchTimeSheets = () => {
    form.get(route("timesheets.index"), {
        onSuccess: (page) => {
            if (page.props.timesheets.length !== timesheets.value.length) {
                timesheets.value = page.props.timesheets;
            }
        },
        preserveState: true,
    });
};

const submit = () => {
    form.post(route("timesheets.upload"), {
        onSuccess: (response) => {
            console.log("Upload success:", response);
            toast.add({
                severity: "success",
                summary: "File Uploaded",
                detail: "The file was uploaded successfully.",
                life: 3000,
            });
            fetchTimeSheets();
            form.reset("file");
            setKey((prevKey) => prevKey + 1);
        },
        onError: (errors) => {
            console.log("Upload error:", errors);
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "There was an error uploading the file.",
                life: 3000,
            });
        },
    });
};

onMounted(() => {
    fetchTimeSheets();
    interval.value = setInterval(fetchTimeSheets, 7000);
});

onUnmounted(() => {
    if (interval.value) {
        clearInterval(interval.value);
    }
});

defineOptions({
    layout: MenubarLayout,
});
</script>

<template>
    <Head :title="`${$page.component}`" />
    <Toast />
    <div class="w-full flex items-center justify-center">
        <div class="h-auto w-5/6 flex flex-col mt-10">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-4xl">TIME SHEETS</h1>
                <form @submit.prevent="submit" class="flex items-center gap-4">
                    <div class="flex flex-col">
                        <input
                            type="file"
                            @change="form.file = $event.target.files[0]"
                            accept=".csv"
                            class="mb-2"
                        />
                        <progress
                            v-if="form.progress"
                            :value="form.progress.percentage"
                            max="100"
                            class="w-full"
                        >
                            {{ form.progress.percentage }}%
                        </progress>
                    </div>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"
                        :disabled="!form.file || form.processing"
                    >
                        {{ form.processing ? "Uploading..." : "Upload File" }}
                    </button>
                </form>
            </div>
            <Divider />

            <!-- DataTable -->
            <div class="card">
                <DataTable :value="timesheets" tableStyle="min-width: 50rem">
                    <Column field="start_time" header="Date">
                        <template #body="slotProps">
                            {{ formatDate(slotProps.data.start_time) }}
                        </template>
                    </Column>
                    <Column field="email" header="Email"></Column>
                    <Column field="description" header="Description"></Column>
                    <Column field="duration" header="Duration">
                        <template #body="slotProps">
                            {{ formatDuration(slotProps.data.duration) }}
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </div>
</template>
