<script setup lang="ts">
import { formatGs } from '@/lib/money';
import type { Product } from '@/types/pos';

defineProps<{ product: Product }>();
const emit = defineEmits<{ add: [product: Product] }>();
</script>

<template>
    <button @click="emit('add', product)" :disabled="product.stock < 1"
        class="group w-full p-4 bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-2 hover:ring-blue-400 text-left disabled:opacity-40 disabled:cursor-not-allowed transition-all flex flex-col">
        <div v-if="product.category"
            class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold mb-1 truncate">
            {{ product.category.name }}
        </div>
        <div class="font-semibold text-sm text-slate-900 leading-tight line-clamp-2 min-h-10">
            {{ product.name }}
        </div>
        <div class="text-xs text-slate-400 mt-1">{{ product.sku }}</div>
        <div class="mt-auto pt-3 flex justify-between items-end gap-2">
            <span class="text-lg font-bold text-blue-600 whitespace-nowrap">
                {{ formatGs(product.price) }}
            </span>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full whitespace-nowrap" :class="product.stock <= 5
                ? 'bg-red-100 text-red-700'
                : product.stock <= 20
                    ? 'bg-amber-100 text-amber-700'
                    : 'bg-emerald-100 text-emerald-700'
                ">
                {{ product.stock }}
            </span>
        </div>
    </button>
</template>