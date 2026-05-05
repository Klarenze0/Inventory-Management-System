<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, Link, useForm } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard',    href: '/dashboard'        },
    { title: 'Categories',   href: '/categories'       },
    { title: 'Add Category', href: '/categories/create'},
]

const form = useForm({
    name:        '',
    description: '',
})

const submit = () => {
    form.post('/categories')
}
</script>

<template>
    <Head title="Add Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 max-w-lg">
            <div class="bg-white dark:bg-sidebar rounded-xl border border-sidebar-border/70 p-6">

                <h2 class="text-lg font-semibold mb-6">Add New Category</h2>

                <form @submit.prevent="submit" class="space-y-4">

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Category Name *</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Electronics"
                        />
                        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="w-full border border-sidebar-border/70 rounded-lg px-3 py-2 text-sm bg-transparent focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Optional description..."
                        />
                        <p v-if="form.errors.description" class="text-red-500 text-xs mt-1">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-primary text-primary-foreground px-6 py-2 rounded-lg text-sm hover:opacity-90 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving...' : 'Save Category' }}
                        </button>
                        <Link
                            href="/categories"
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