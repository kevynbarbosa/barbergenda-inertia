<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { toUrl, urlIsActive } from '@/lib/utils';
import { appearance } from '@/routes';
import { edit as editPassword } from '@/routes/password';
import { edit } from '@/routes/profile';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Perfil',
        href: edit(),
    },
    {
        title: 'Senha',
        href: editPassword(),
    },
    {
        title: 'Aparência',
        href: appearance(),
    },
];

const currentPath = typeof window !== 'undefined' ? window.location.pathname : '';
</script>

<template>
    <div class="flex flex-col lg:flex-row lg:space-x-12">
        <aside class="w-full max-w-xl lg:w-48">
            <nav class="flex flex-col space-y-1 space-x-0">
                <Button
                    v-for="item in sidebarNavItems"
                    :key="toUrl(item.href)"
                    variant="ghost"
                    :class="['w-full justify-start', { 'bg-primary text-primary-foreground': urlIsActive(item.href, currentPath) }]"
                    as-child
                >
                    <Link :href="item.href">
                        {{ item.title }}
                    </Link>
                </Button>
            </nav>
        </aside>

        <Separator class="my-6 lg:hidden" />

        <div class="flex-1 md:max-w-2xl">
            <slot />
        </div>
    </div>
</template>