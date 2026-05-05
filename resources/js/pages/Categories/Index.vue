<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard',  href: '/dashboard'  },
    { title: 'Categories', href: '/categories' },
]

const page  = usePage()
const flash = page.props.flash as any

withDefaults(defineProps<{
    categories: {
        data: Array<{
            id:             number
            name:           string
            description:    string | null
            products_count: number
        }>
        links: Array<{ url: string | null, label: string, active: boolean }>
        total: number
    }
}>(), {
    categories: () => ({
        data:  [],
        links: [],
        total: 0,
    })
})

const deleteCategory = (id: number) => {
    if (confirm('Are you sure you want to delete this category?')) {
        router.delete(`/categories/${id}`)
    }
}
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">

            <!-- Flash Messages -->
            <div v-if="flash?.success" class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">
                ✅ {{ flash.success }}
            </div>
            <div v-if="flash?.error" class="bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm">
                ❌ {{ flash.error }}
            </div>

            <!-- Header -->
            <div class="flex items-center justify-between">
                <p class="text-sm text-muted-foreground">{{ categories.total }} categories total</p>
                <Link
                    href="/categories/create"
                    class="bg-primary text-primary-foreground px-4 py-2 rounded-lg text-sm hover:opacity-90"
                >
                    + Add Category
                </Link>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-sidebar-border/70 overflow-hidden bg-white dark:bg-sidebar">
                <table class="w-full text-sm">
                    <thead class="bg-muted text-muted-foreground uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Description</th>
                            <th class="px-4 py-3 text-left">Products</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sidebar-border/70">
                        <tr
                            v-for="category in categories.data"
                            :key="category.id"
                            class="hover:bg-muted/50 transition-colors"
                        >
                            <td class="px-4 py-3 font-medium">{{ category.name }}</td>
                            <td class="px-4 py-3 text-muted-foreground">
                                {{ category.description ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-medium">
                                    {{ category.products_count }} products
                                </span>
                            </td>
                            <td class="px-4 py-3 flex gap-3">
                                <Link
                                    :href="`/categories/${category.id}/edit`"
                                    class="text-blue-500 hover:underline text-xs"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteCategory(category.id)"
                                    class="text-red-500 hover:underline text-xs"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>

                        <tr v-if="categories.data.length === 0">
                            <td colspan="4" class="px-4 py-8 text-center text-muted-foreground">
                                No categories found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex gap-2 flex-wrap">
                <Link
                    v-for="link in categories.links"
                    :key="link.label"
                    :href="link.url ?? '#'"
                    v-html="link.label"
                    class="px-3 py-1 rounded text-sm border border-sidebar-border/70"
                    :class="link.active
                        ? 'bg-primary text-primary-foreground'
                        : 'bg-white dark:bg-sidebar text-muted-foreground hover:bg-muted'"
                />
            </div>

        </div>
    </AppLayout>
</template>