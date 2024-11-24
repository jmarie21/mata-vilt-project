<script setup>
import MenubarLayout from '../Layouts/MenubarLayout.vue';

defineOptions({
    layout: MenubarLayout
})
</script>

<template>
    <Head :title="`${$page.component}`" />
    <div class="h-screen flex items-center justify-center">
        <h1>PROJECTS</h1>
    </div>
</template>