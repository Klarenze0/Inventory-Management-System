<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Products',  href: '/products'  },
]

const page = usePage()

const flash = page.props.flash as any

withDefaults(defineProps<{
    products: {
        data: Array<{
            id:                  number
            name:                string
            sku:                 string
            unit_price:          number
            stock_quantity:      number
            low_stock_threshold: number
            category:            { name: string } | null
        }>
        links: Array<{ url: string | null, label: string, active: boolean }>
        total: number
    }
}>(), {
    products: () => ({
        data:  [],
        links: [],
        total: 0,
    })
})

const stockBadge = (product: any) => {
    if (product.stock_quantity <= 0)
        return { label: 'Out of Stock', class: 'bg-red-100 text-red-700' }
    if (product.stock_quantity <= product.low_stock_threshold)
        return { label: 'Low Stock',    class: 'bg-yellow-100 text-yellow-700' }
    return   { label: 'In Stock',      class: 'bg-green-100 text-green-700' }
}

const deleteProduct = (id: number) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(`/products/${id}`)
    }
}
</script>

<template>
    <Head title="Products" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">

            <!-- Flash Messages -->
            <div v-if="flash?.success" class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">
                {{ flash.success }}
            </div>
            <div v-if="flash?.error" class="bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ flash.error }}
            </div>

            <!-- Header -->
            <div class="flex items-center justify-between">
                <p class="text-sm text-muted-foreground">{{ products.total }} products total</p>
                <Link
                    href="/products/create"
                    class="bg-primary text-primary-foreground px-4 py-2 rounded-lg text-sm hover:opacity-90"
                >
                    + Add Product
                </Link>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-sidebar-border/70 overflow-hidden bg-white dark:bg-sidebar">
                <table class="w-full text-sm">
                    <thead class="bg-muted text-muted-foreground uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Product</th>
                            <th class="px-4 py-3 text-left">SKU</th>
                            <th class="px-4 py-3 text-left">Category</th>
                            <th class="px-4 py-3 text-left">Price</th>
                            <th class="px-4 py-3 text-left">Stock</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sidebar-border/70">
                        <tr
                            v-for="product in products.data"
                            :key="product.id"
                            class="hover:bg-muted/50 transition-colors"
                        >
                            <td class="px-4 py-3 font-medium">{{ product.name }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ product.sku }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ product.category?.name ?? '—' }}</td>
                            <td class="px-4 py-3">₱{{ product.unit_price }}</td>
                            <td class="px-4 py-3">{{ product.stock_quantity }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium"
                                    :class="stockBadge(product).class"
                                >
                                    {{ stockBadge(product).label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 flex gap-3">
                                <Link
                                    :href="`/products/${product.id}/edit`"
                                    class="text-blue-500 hover:underline text-xs"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteProduct(product.id)"
                                    class="text-red-500 hover:underline text-xs"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>

                        <!-- Empty state -->
                        <tr v-if="products.data.length === 0">
                            <td colspan="7" class="px-4 py-8 text-center text-muted-foreground">
                                No products found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex gap-2 flex-wrap">
                <Link
                    v-for="link in products.links"
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