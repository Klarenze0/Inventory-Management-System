<script setup lang="ts">
import { BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard'},
]

const page = usePage()

const flash = page.props.flash as any

const props = defineProps<{
    totalProducts:      number
    totalCategories:    number
    totalSuppliers:     number
    lowStockCount:      number
    outOfStockCount:    number
}>()

</script>

<template>
    <Head title="Dashboard"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-sidebar rounded-x1 border border-sidebar-border/70 p-5">
                    <p class="text-sm text-muted-foreground">Total Products</p>
                    <p class="text-3xl font-bold mt-1">{{ totalProducts }}</p>
                </div>

                <div class="bg-white dark:bg-sidebar rounded-x1 border border-sidebar-border/70 p-5">
                    <p class="text-sm text-muted-foreground">Categories</p>
                    <p class="text-3xl font-bold mt-1">{{ totalCategories }}</p>
                </div>

                <div class="bg-white dark:bg-sidebar rounded-x1 border border-sidebar-border/70 p-5">
                    <p class="text-sm text-muted-foreground">Suppliers</p>
                    <p class="text-3xl font-bold mt-1">{{ totalSuppliers }}</p>
                </div>

                <div class="bg-white dark:bg-sidebar rounded-x1 border border-sidebar-border/70 p-5">
                    <p class="text-sm text-muted-foreground">Low Stock</p>
                    <p class="text-3xl font-bold text-yellow-500 mt-1">{{ lowStockCount }}</p>
                </div>

                <div class="bg-white dark:bg-sidebar rounded-x1 border border-sidebar-border/70 p-5">
                    <p class="text-sm text-muted-foreground">Out of Stock</p>
                    <p class="text-3xl font-bold text-red-500 mt-1">{{ outOfStockCount }}</p>
                </div>
            </div>

            <!-- Flash messages -->
             <div v-if="flash?.success || flash?.error">
                <div v-if="flash?.success"
                    class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ flash.success }}
                </div>
                <div v-if="flash?.error"
                    class="bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm">
                    {{ flash.error }}
                </div>
            </div>

        </div>

    </AppLayout>

</template>