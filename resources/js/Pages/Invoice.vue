<script setup>
import MenubarLayout from '../Layouts/MenubarLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Divider from 'primevue/divider';
import TextInput from './Components/TextInput.vue';
import Toast from 'primevue/toast';


import { ref } from "vue";
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { useToast } from "primevue/usetoast";

const addNewInvoiceModal = ref(false);
const editInvoiceModal = ref(false);
const downloading = ref(false);
const toast = useToast();

const props = defineProps({
    invoice: [Array, Object]
});

const openNewInvoiceModal = () => {
    addNewInvoiceModal.value = true;
};



const addNewInvoiceForm = useForm({
    client_name: null,
    email: null,
    bill: null,
});

const editInvoiceForm = useForm({
    client_name: props.invoice.client_name,
    email: props.invoice.email,
    bill: props.invoice.bill,
});

const addNewInvoice = () => {
    addNewInvoiceForm.post(route('invoice.create'), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Added', detail: 'Invoice added successfully!', life: 3000 });
            addNewInvoiceForm.reset();
            addNewInvoiceModal.value = false;
        },
        onError: () => {
            
        }
    })
};

const editInvoice = (invoice) => {
    editInvoiceForm.id = invoice.id;
    editInvoiceForm.client_name = invoice.client_name;
    editInvoiceForm.email = invoice.email;
    editInvoiceForm.bill = invoice.bill;
    editInvoiceForm.id = invoice.id;
    editInvoiceModal.value = true;
};

const saveEditedInvoice = () => {
    editInvoiceForm.put(route('invoice.edit', editInvoiceForm.id), {
        onSuccess: () => {
            editInvoiceForm.reset();
            editInvoiceModal.value = false;
            toast.add({ severity: 'success', summary: 'Saved', detail: 'Edited successfully!', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update the invoice.', life: 3000 });
        }
    })
};

const deleteInvoice = (id) => {
    editInvoiceForm.delete(route('invoice.delete', id), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Deleted', detail: 'Invoice deleted successfully!', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to delete the invoice.', life: 3000 });
        }
    })
}

const downloadInvoice = async (id) => {
    downloading.value = true;
    try {
        window.open(route('invoice.download', id), '_blank');
    } catch (error) {
        console.error('Error downloading invoice:', error)
    } finally {
        downloading.value = false
    }
};

const sendInvoice = async (id) => {
    router.post(route('invoice.send', id), {}, {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Sent', detail: 'Invoice will be sent shortly.', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to send invoice', life: 3000 });
        }
    });
};



defineOptions({
    layout: MenubarLayout
})
</script>

<template>
    <Head :title="`${$page.component}`" />
    <Toast />
    <div class="w-full flex items-center justify-center">
        <div class="h-auto w-5/6 flex flex-col mt-10">
            <div class="flex items-center justify-between">
                <h1 class="text-4xl">INVOICE</h1>
                <Button label="New Invoice" icon="pi pi-plus" class="mr-4" severity="success" @click="openNewInvoiceModal" />
                <Dialog v-model:visible="addNewInvoiceModal" modal header="Add New Invoice" :style="{ width: '500px' }">
                    <form @submit.prevent="addNewInvoice">
                        <div class="flex flex-col gap-4 mb-2">
                            <TextInput id="name" v-model="addNewInvoiceForm.client_name" class="flex-auto" autocomplete="off" :message="addNewInvoiceForm.errors.client_name" placeholder="Client Name" />
                            <TextInput id="email" v-model="addNewInvoiceForm.email" class="flex-auto" autocomplete="off" :message="addNewInvoiceForm.errors.email" placeholder="Email" />
                            <TextInput id="bill" v-model="addNewInvoiceForm.bill" class="flex-auto" autocomplete="off" :message="addNewInvoiceForm.errors.bill" placeholder="Bill" />
                        </div>
                        <div class="flex justify-end gap-2">
                            <Button type="button" label="Cancel" severity="secondary" @click="addNewInvoiceModal = false"></Button>
                            <Button type="submit" label="Add"></Button>
                        </div>
                    </form>
                </Dialog>
            </div>
            <Divider />
            <div class="card">
                <DataTable :value="invoice" tableStyle="min-width: 50rem">
                    <Column field="client_name" header="Client Name"></Column>
                    <Column field="email" header="Email"></Column>
                    <Column field="bill" header="Total Bill"></Column>
                    <Column field="status" header="Status"></Column>
                    <Column header="Action">
                        <template #body="{ data }">
                            <Button v-tooltip.bottom="'Edit'" size="small" icon="pi pi-pencil" class="mr-2" severity="info" @click="editInvoice(data)" />
                            <Dialog v-model:visible="editInvoiceModal" modal header="Edit Invoice" :style="{ width: '500px' }">
                                <form @submit.prevent="saveEditedInvoice">
                                    <div class="flex flex-col gap-4 mb-2">
                                        <TextInput id="name" v-model="editInvoiceForm.client_name" class="flex-auto" autocomplete="off" :message="editInvoiceForm.errors.client_name" placeholder="Client Name" />
                                        <TextInput id="email" v-model="editInvoiceForm.email" class="flex-auto" autocomplete="off" :message="editInvoiceForm.errors.email" placeholder="Email" />
                                        <TextInput id="bill" v-model="editInvoiceForm.bill" class="flex-auto" autocomplete="off" :message="editInvoiceForm.errors.bill" placeholder="Bill" />
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <Button type="button" label="Cancel" severity="secondary" @click="editInvoiceModal = false"></Button>
                                        <Button type="submit" label="Save"></Button>
                                    </div>
                                </form>
                            </Dialog>
                            <Button v-tooltip.bottom="'Delete'" size="small" icon="pi pi-trash" class="mr-2" severity="danger" @click="deleteInvoice(data.id)" />
                            <Button v-tooltip.bottom="'Download PDF'" size="small" icon="pi pi-file-pdf" class="mr-2" severity="help" :loading="downloading" @click="downloadInvoice(data.id)" />
                            <Button v-tooltip.bottom="'Send Invoice'" size="small" icon="pi pi-send" class="mr-2" severity="success" @click="sendInvoice(data.id)" />
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </div>
</template>