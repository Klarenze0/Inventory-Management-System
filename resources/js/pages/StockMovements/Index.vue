<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, Link, usePage } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard',       href: '/dashboard'       },
    { title: 'Stock Movements', href: '/stock-movements' },
]

const page  = usePage()
const flash = page.props.flash as any

withDefaults(defineProps<{
    movements: {
        data: Array<{
            id:           number
            type:         'in' | 'out' | 'adjustment'
            quantity:     number
            reason:       string | null
            stock_before: number
            stock_after:  number
            created_at:   string
            product: { name: string, sku: string }
            user:    { name: string }
        }>
        links: Array<{ url: string | null, label: string, active: boolean }>
        total: number
    }
}>(), {
    movements: () => ({
        data:  [],
        links: [],
        total: 0,
    })
})

const typeBadge = (type: string) => {
    if (type === 'in')
        return { label: 'Stock In',    class: 'bg-green-100 text-green-700'  }
    if (type === 'out')
        return { label: 'Stock Out',   class: 'bg-red-100 text-red-700'     }
    return       { label: 'Adjustment', class: 'bg-blue-100 text-blue-700'  }
}

const formatDate = (dateStr: string) => {
    return new Date(dateStr).toLocaleDateString('en-PH', {
        year:  'numeric',
        month: 'short',
        day:   'numeric',
        hour:  '2-digit',
        minute:'2-digit',
    })
}
</script>

<template>
    <Head title="Stock Movements" />

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
                <p class="text-sm text-muted-foreground">{{ movements.total }} movements total</p>
                <Link
                    href="/stock-movements/create"
                    class="bg-primary text-primary-foreground px-4 py-2 rounded-lg text-sm hover:opacity-90"
                >
                    + Record Movement
                </Link>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-sidebar-border/70 overflow-hidden bg-white dark:bg-sidebar">
                <table class="w-full text-sm">
                    <thead class="bg-muted text-muted-foreground uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Product</th>
                            <th class="px-4 py-3 text-left">Type</th>
                            <th class="px-4 py-3 text-left">Quantity</th>
                            <th class="px-4 py-3 text-left">Before</th>
                            <th class="px-4 py-3 text-left">After</th>
                            <th class="px-4 py-3 text-left">Reason</th>
                            <th class="px-4 py-3 text-left">By</th>
                            <th class="px-4 py-3 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sidebar-border/70">
                        <tr
                            v-for="movement in movements.data"
                            :key="movement.id"
                            class="hover:bg-muted/50 transition-colors"
                        >
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ movement.product.name }}</p>
                                <p class="text-xs text-muted-foreground">{{ movement.product.sku }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium"
                                    :class="typeBadge(movement.type).class"
                                >
                                    {{ typeBadge(movement.type).label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium">{{ movement.quantity }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ movement.stock_before }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ movement.stock_after }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ movement.reason ?? '—' }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ movement.user.name }}</td>
                            <td class="px-4 py-3 text-muted-foreground text-xs">{{ formatDate(movement.created_at) }}</td>
                        </tr>

                        <tr v-if="movements.data.length === 0">
                            <td colspan="8" class="px-4 py-8 text-center text-muted-foreground">
                                No stock movements recorded yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex gap-2 flex-wrap">
                <Link
                    v-for="link in movements.links"
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