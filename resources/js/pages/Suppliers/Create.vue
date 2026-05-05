<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, Link, useForm } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard',    href: '/dashboard'       },
    { title: 'Suppliers',    href: '/suppliers'       },
    { title: 'Add Supplier', href: '/suppliers/create'},
]

const form = useForm({
    company_name:   '',
    contact_person: '',
    email:          '',
    phone:          '',
    address:        '',
})

const submit = () => {
    form.post('/suppliers')
}
</script>

<template>
    <Head title="Add Supplier" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 max-w-lg">
            <div class="bg-white dark:bg-sidebar rounded-xl border border-sidebar-border/70 p-6">

                <h2 class="text-lg font-semibold mb-6">Add New Supplier</h2>

                <form @submit.prevent="submit" class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium mb-1">Company Name *</label>
                        <input
                            v-model="form.company_name"
                            type="text"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="ABC Supplies Co."
                        />
                        <p v-if="form.errors.company_name" class="text-red-500 text-xs mt-1">
                            {{ form.errors.company_name }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Contact Person</label>
                        <input
                            v-model="form.contact_person"
                            type="text"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Juan dela Cruz"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="supplier@example.com"
                        />
                        <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Phone</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="+63 912 345 6789"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Address</label>
                        <textarea
                            v-model="form.address"
                            rows="3"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="123 Main St, Manila"
                        />
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-primary text-primary-foreground px-6 py-2 rounded-lg text-sm hover:opacity-90 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving...' : 'Save Supplier' }}
                        </button>
                        <Link
                            href="/suppliers"
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