import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: {
        id: number;
        name: string;
        email: string;
        role?: {
            name: string;
        } | null;
    };
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    url: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: {
        location: string;
        url: string;
        port: null | number;
        defaults: Record<string, unknown>;
        routes: Record<string, string>;
    };
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface FlashMessages {
    success?: string | null;
    error?: string | null;
}

declare module '@inertiajs/vue3' {
    interface PageProps {
        flash: FlashMessages;
        auth: Auth;
    }
}

export type BreadcrumbItemType = BreadcrumbItem;
