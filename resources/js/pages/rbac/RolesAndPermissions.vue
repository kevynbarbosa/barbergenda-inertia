<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />
        <h3>RBAC Roles and Permissions Page</h3>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>
