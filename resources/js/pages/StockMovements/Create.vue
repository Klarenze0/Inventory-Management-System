<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, Link, useForm } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard',        href: '/dashboard'              },
    { title: 'Stock Movements',  href: '/stock-movements'        },
    { title: 'Record Movement',  href: '/stock-movements/create' },
]

defineProps<{
    products: Array<{
        id:             number
        name:           string
        sku:            string
        stock_quantity: number
    }>
}>()

const form = useForm({
    product_id: '',
    type:       'in',
    quantity:   1,
    reason:     '',
})

const submit = () => {
    form.post('/stock-movements')
}
</script>

<template>
    <Head title="Record Stock Movement" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 max-w-lg">
            <div class="bg-white dark:bg-sidebar rounded-xl border border-sidebar-border/70 p-6">

                <h2 class="text-lg font-semibold mb-6">Record Stock Movement</h2>

                <form @submit.prevent="submit" class="space-y-4">

                    <!-- Product -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Product *</label>
                        <select
                            v-model="form.product_id"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:bg-background focus:outline-none focus:ring-2 focus:ring-primary"
                        >
                            <option value="">-- Select Product --</option>
                            <option
                                v-for="product in products"
                                :key="product.id"
                                :value="product.id"
                            >
                                {{ product.name }} ({{ product.sku }}) — Stock: {{ product.stock_quantity }}
                            </option>
                        </select>
                        <p v-if="form.errors.product_id" class="text-red-500 text-xs mt-1">
                            {{ form.errors.product_id }}
                        </p>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Movement Type *</label>
                        <div class="flex gap-3">
                            <label
                                v-for="option in [
                                    { value: 'in',         label: 'Stock In',    class: 'bg-green-100 text-green-700 border-green-300' },
                                    { value: 'out',        label: 'Stock Out',   class: 'bg-red-100 text-red-700 border-red-300'       },
                                    { value: 'adjustment', label: 'Adjustment',  class: 'bg-blue-100 text-blue-700 border-blue-300'    },
                                ]"
                                :key="option.value"
                                class="flex-1 border rounded-lg px-3 py-2 text-sm text-center cursor-pointer transition-all"
                                :class="form.type === option.value
                                    ? option.class + ' font-semibold'
                                    : 'border-sidebar-border/70 text-muted-foreground hover:bg-muted'"
                            >
                                <input
                                    type="radio"
                                    :value="option.value"
                                    v-model="form.type"
                                    class="hidden"
                                />
                                {{ option.label }}
                            </label>
                        </div>
                        <p v-if="form.errors.type" class="text-red-500 text-xs mt-1">
                            {{ form.errors.type }}
                        </p>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Quantity *
                            <span v-if="form.type === 'adjustment'" class="text-xs text-muted-foreground font-normal">
                                (negative para magbawas, positive para magdagdag)
                            </span>
                        </label>
                        <input
                            v-model="form.quantity"
                            type="number"
                            :min="form.type === 'adjustment' ? undefined : 1"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                        />
                        <p v-if="form.errors.quantity" class="text-red-500 text-xs mt-1">
                            {{ form.errors.quantity }}
                        </p>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Reason</label>
                        <textarea
                            v-model="form.reason"
                            rows="3"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Delivery from supplier, sold to customer, etc."
                        />
                    </div>
                    <div v-if="(form.errors as any).error" class="bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm">
                        ❌ {{ (form.errors as any).error }}
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-primary text-primary-foreground px-6 py-2 rounded-lg text-sm hover:opacity-90 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Recording...' : 'Record Movement' }}
                        </button>
                        <Link
                            href="/stock-movements"
                            class="bg-muted text-muted-foreground px-6 py-2 rounded-lg text-sm hover:opacity-90"
                        >
                            Cancel
                        </Link>
                    </div>

                </form>
            </div>
        </div>
    </AppLayout>
</template>