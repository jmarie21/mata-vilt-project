<script setup>

import Divider from "primevue/divider";
import MenubarLayout from "../Layouts/MenubarLayout.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from "primevue/dialog"
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';

// State for the dialog and generated code
const displayDialog = ref(false);
const generatedCode = ref('');

const { codes } = usePage().props;

console.log(codes)

const generateCode = () => {
    router.post('/codes', {}, {
        preserveScroll: true,
        onSuccess: (response) => {
            generatedCode.value = response.props.codes[response.props.codes.length - 1].code;
            displayDialog.value = true;
        }
    })
}

// Function to copy the generated code to the clipboard
const copyCode = () => {
    navigator.clipboard.writeText(generatedCode.value).then(() => {
        // Optionally, you can add a toast or notification here
        alert('Code copied to clipboard!');
    });
};

// Function to close the dialog
const closeDialog = () => {
    displayDialog.value = false;
};



defineOptions({
    layout: MenubarLayout,  
});

</script>

<template>
    <Head :title="`${$page.component}`" />
    <div class="w-full flex items-center justify-center">
        <div class="h-auto w-full flex flex-col mt-10 px-16">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-4xl">Codes</h1>
                <Button label="Generate Code" class="mr-4" severity="success" @click="generateCode" />
            </div>
            <Divider />
            <DataTable :value="codes" class="mt-4">
                <Column field="code" header="Code"></Column>
            </DataTable>
        </div>
    </div>

    <!-- Dialog to display the generated code -->
    <Dialog v-model:visible="displayDialog" modal header="Generated Code" :style="{ width: '400px' }">
        <div class="flex flex-col items-center justify-center">
            <p class="text-lg font-semibold mb-4">{{ generatedCode }}</p>
            <div class="flex gap-2">
                <Button label="Copy" severity="info" @click="copyCode" />
                <Button label="Close" severity="secondary" @click="closeDialog" />
            </div>
        </div>
    </Dialog>
</template>