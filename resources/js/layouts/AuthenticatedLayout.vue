<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth.user)

const sidebarOpen = ref(true)

const navigation = [
    { name: 'Dashboard',        href: route('dashboard'),              icon: '📊' },
    { name: 'Products',         href: route('products.index'),         icon: '📦' },
    { name: 'Categories',       href: route('categories.index'),       icon: '🗂️'  },
    { name: 'Suppliers',        href: route('suppliers.index'),        icon: '🏭' },
    { name: 'Stock Movements',  href: route('stock-movements.index'),  icon: '🔄' },
]

const isActive = (routeName) => {
    try {
        const path = route(routeName, {}, false)

        if (!path || !page.url) return false

        return page.url.startsWith(path)
    } catch (e) {
        return false
    }
}

console.log(navigation)
navigation.forEach(item => {
    console.log(item.route, route(item.route))
})

</script>

<template>
    <div class="flex h-screen bg-gray-100">

        <!-- ─── Sidebar ─────────────────────────────── -->
        <aside
            class="bg-gray-900 text-white transition-all duration-300"
            :class="sidebarOpen ? 'w-64' : 'w-16'"
        >
            <!-- Logo -->
            <div class="flex items-center justify-between p-4 border-b border-gray-700">
                <span v-if="sidebarOpen" class="text-lg font-bold">📋 Inventory</span>
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-400 hover:text-white"
                >
                    {{ sidebarOpen ? '◀' : '▶' }}
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="p-2 space-y-1">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="route(item.route)"
                    :class="{ 'bg-gray-700 text-white': isActive(item.route) }"
                >
                    <span class="text-xl">{{ item.icon }}</span>
                    <span v-if="sidebarOpen" class="text-sm font-medium">{{ item.name }}</span>
                </Link>
            </nav>
        </aside>

        <!-- ─── Main Content ─────────────────────────── -->
        <div class="flex flex-col flex-1 overflow-hidden">

            <!-- Top Bar -->
            <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-800">
                    <slot name="header" />
                </h1>

                <!-- User Info + Logout -->
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">
                        👤 {{ user.name }}
                        <span class="ml-1 text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                            {{ user.role?.name ?? 'No Role' }}
                        </span>
                    </span>
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="text-sm text-red-500 hover:text-red-700"
                    >
                        Logout
                    </Link>
                </div>
            </header>

            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.success || $page.props.flash?.error" class="px-6 pt-4">
                <div
                    v-if="$page.props.flash?.success"
                    class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm"
                >
                    ✅ {{ $page.props.flash.success }}
                </div>
                <div
                    v-if="$page.props.flash?.error"
                    class="bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm"
                >
                    ❌ {{ $page.props.flash.error }}
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>

        </div>
    </div>
</template>