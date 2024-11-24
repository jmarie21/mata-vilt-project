<script setup>
import InputText from 'primevue/inputtext';

const model = defineModel({
    type: null,
    required: true,
})

defineProps({
    type: {
        type: String,
        default: 'text',
    },
    message: String,
    placeholder: String,
    id: String,
})
</script>

<template>
    <div>
        <InputText :id="id" :type="type" :placeholder="placeholder" :class="{'mb-2 w-full': !message, 'p-inputtext-sm mb-2 w-full p-invalid': message}" v-model='model' />
        <small class="text-red-500 mt-0" v-if="message">{{ message }}</small>
    </div>
</template>