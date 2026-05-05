<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Suppliers', href: '/suppliers' },
]

const page  = usePage()
const flash = page.props.flash as any

withDefaults(defineProps<{
    suppliers: {
        data: Array<{
            id:             number
            company_name:   string
            contact_person: string | null
            email:          string | null
            phone:          string | null
            address:        string | null
        }>
        links: Array<{ url: string | null, label: string, active: boolean }>
        total: number
    }
}>(), {
    suppliers: () => ({
        data:  [],
        links: [],
        total: 0,
    })
})

const deleteSupplier = (id: number) => {
    if (confirm('Are you sure you want to delete this supplier?')) {
        router.delete(`/suppliers/${id}`)
    }
}
</script>

<template>
    <Head title="Suppliers" />

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
                <p class="text-sm text-muted-foreground">{{ suppliers.total }} suppliers total</p>
                <Link
                    href="/suppliers/create"
                    class="bg-primary text-primary-foreground px-4 py-2 rounded-lg text-sm hover:opacity-90"
                >
                    + Add Supplier
                </Link>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-sidebar-border/70 overflow-hidden bg-white dark:bg-sidebar">
                <table class="w-full text-sm">
                    <thead class="bg-muted text-muted-foreground uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Company</th>
                            <th class="px-4 py-3 text-left">Contact Person</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Phone</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sidebar-border/70">
                        <tr
                            v-for="supplier in suppliers.data"
                            :key="supplier.id"
                            class="hover:bg-muted/50 transition-colors"
                        >
                            <td class="px-4 py-3 font-medium">{{ supplier.company_name }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ supplier.contact_person ?? '—' }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ supplier.email ?? '—' }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ supplier.phone ?? '—' }}</td>
                            <td class="px-4 py-3 flex gap-3">
                                <Link
                                    :href="`/suppliers/${supplier.id}/edit`"
                                    class="text-blue-500 hover:underline text-xs"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteSupplier(supplier.id)"
                                    class="text-red-500 hover:underline text-xs"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>

                        <tr v-if="suppliers.data.length === 0">
                            <td colspan="5" class="px-4 py-8 text-center text-muted-foreground">
                                No suppliers found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex gap-2 flex-wrap">
                <Link
                    v-for="link in suppliers.links"
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