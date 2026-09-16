<script setup lang="ts">
import ProductCard from './ProductCard.vue';
import type { Product } from '@/types/pos';

defineProps<{
    products: Product[];
    loading: boolean;
    search: string;
}>();

const emit = defineEmits<{ add: [product: Product] }>();
</script>

<template>
    <div class="mt-4 grid gap-3 overflow-y-auto pb-4 flex-1"
        style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr))">
        <ProductCard v-for="p in products" :key="p.id" :product="p" @add="emit('add', $event)" />

        <div v-if="!loading && products.length === 0"
            class="col-span-full flex flex-col items-center justify-center py-16 text-slate-400">
            <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <span class="text-sm">
                {{ search ? 'Sin resultados' : 'Sin productos disponibles' }}
            </span>
        </div>
    </div>
</template>