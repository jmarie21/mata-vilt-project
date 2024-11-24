<script setup>
import MenubarLayout from '../Layouts/MenubarLayout.vue';

defineOptions({
    layout: MenubarLayout
})
</script>

<template>
    <Head :title="`${$page.component}`"/>
    <div class="h-screen flex items-center justify-center">
        <div class="">
            <h1 class="text-5xl">MY APP</h1>
        </div>
    </div>
</template>