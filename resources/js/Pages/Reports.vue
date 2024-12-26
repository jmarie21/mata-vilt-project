<script setup>
import Divider from "primevue/divider";
import MenubarLayout from "../Layouts/MenubarLayout.vue";

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
            </div>
        </div>
    </div>
</template>
