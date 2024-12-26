<script setup>
import MenubarLayout from "../Layouts/MenubarLayout.vue";
import Divider from "primevue/divider";
import Button from "primevue/button";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const toast = useToast();
const isLoading = ref(false);
const interval = ref(""); // Bind to the selected scheduler interval

const props = defineProps({
    scheduleInterval: {
        type: String,
        default: "", // Default interval from backend
    },
});

interval.value = props.scheduleInterval; // Initialize with the value from backend

const submitForm = () => {
    isLoading.value = true;

    router.post(
        "/config",
        { interval: interval.value },
        {
            onStart: () => {
                toast.add({
                    severity: "info",
                    summary: "Saving",
                    detail: "Submitting scheduler interval",
                    life: 2000,
                });
            },
            onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: "Success",
                    detail: "Interval saved successfully",
                    life: 3000,
                });
            },
            onError: (error) => {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: "Failed to save interval",
                    life: 3000,
                });
                console.error("Error saving interval: ", error);
            },
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
};

defineOptions({
    layout: MenubarLayout,
});
</script>

<template>
    <Head :title="`${$page.component}`" />
    <div class="w-full flex items-center justify-center">
        <div class="h-auto w-5/6 flex flex-col mt-10">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-4xl">Configuration</h1>
            </div>
            <Divider />

            <!-- Scheduler Configuration Form -->
            <form @submit.prevent="submitForm" class="mt-4">
                <div class="form-group mb-4">
                    <label
                        for="interval"
                        class="block text-lg font-medium mb-2"
                    >
                        Select time interval in fetching clickup tasks:
                    </label>
                    <select
                        v-model="interval"
                        id="interval"
                        class="form-control border rounded-lg px-4 py-2"
                    >
                        <option value="everyMinute">Every Minute</option>
                        <option value="everyFiveMinutes">
                            Every 5 Minutes
                        </option>
                        <option value="hourly">Hourly</option>
                        <option value="daily">Daily</option>
                    </select>
                </div>
                <Button
                    type="submit"
                    label="Save Interval"
                    severity="success"
                    :loading="isLoading"
                />
            </form>
        </div>

        <Toast />
    </div>
</template>
