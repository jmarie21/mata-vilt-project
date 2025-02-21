<script setup>
import Divider from "primevue/divider";
import MenubarLayout from "../Layouts/MenubarLayout.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import { Head, usePage, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { Toast, useToast } from "primevue";

// State for the dialog and generated code
const displayDialog = ref(false);
const generatedCode = ref("");
const toast = useToast();

const { codes } = usePage().props;

console.log(codes);

const generateCode = () => {
    router.post(
        route("codes.generate"),
        {},
        {
            preserveScroll: true,
            onSuccess: (response) => {
                // Get the latest generated code from the updated codes prop
                const latestCode = response.props.codes[0].code; // First item is the latest
                // Update the generatedCode and open the dialog
                generatedCode.value = latestCode;
                displayDialog.value = true;
            },
        }
    );
};

// Function to copy the generated code to the clipboard
const copyCode = () => {
    navigator.clipboard.writeText(generatedCode.value).then(() => {
        // Optionally, you can add a toast or notification here
        toast.add({
            severity: "success",
            summary: "Copied",
            detail: "Code copied to clipboard",
            life: 3000,
        });
    });
};

// Function to close the dialog
const closeDialog = () => {
    displayDialog.value = false;
    generatedCode.value = ""; // Clear the generated code when closing the dialog
};

defineOptions({
    layout: MenubarLayout,
});
</script>

<template>
    <Toast />
    <Head :title="`${$page.component}`" />
    <div class="w-full flex items-center justify-center">
        <div class="h-auto w-full flex flex-col mt-10 px-16">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-4xl">Codes</h1>
                <Button
                    label="Generate Code"
                    class="mr-4"
                    severity="success"
                    @click="generateCode"
                />
            </div>
            <Divider />

            <div v-if="codes && codes.length > 0">
                <DataTable :value="codes" class="mt-4">
                    <Column field="code" header="Code"></Column>
                    <Column
                        field="created_at_formatted"
                        header="Date Created"
                    ></Column>
                </DataTable>
            </div>
            <div v-else class="text-center p-4 bg-gray-100 rounded">
                <p class="text-gray-600">
                    There are no codes as of the moment. Click "Generate code".
                </p>
            </div>
        </div>
    </div>

    <!-- Dialog to display the generated code -->
    <Dialog
        v-model:visible="displayDialog"
        modal
        header="Generated Code"
        :style="{ width: '400px' }"
    >
        <div class="flex flex-col items-center justify-center">
            <p class="text-5xl font-bold mb-8">{{ generatedCode }}</p>
            <div class="flex gap-2">
                <Button label="Copy" severity="success" @click="copyCode" />
                <Button
                    label="Close"
                    severity="secondary"
                    @click="closeDialog"
                />
            </div>
        </div>
    </Dialog>
</template>
