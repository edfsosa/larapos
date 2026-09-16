<script setup lang="ts">
import { formatGs } from '@/lib/money';
import type { CartItem } from '@/types/pos';

defineProps<{ item: CartItem }>();

const emit = defineEmits<{
    decrement: [];
    increment: [];
    remove: [];
}>();
</script>

<template>
    <div class="flex items-center gap-3 p-3 bg-white rounded-lg shadow-sm">
        <div class="flex-1 min-w-0">
            <div class="text-sm font-semibold text-slate-900 truncate">
                {{ item.product.name }}
            </div>
            <div class="text-xs text-slate-500 mt-0.5">
                {{ formatGs(item.product.price) }} × {{ item.quantity }} =
                <span class="font-semibold text-slate-700">
                    {{ formatGs(item.product.price * item.quantity) }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-1">
            <button @click="emit('decrement')"
                class="w-8 h-8 bg-slate-100 rounded-lg hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center">
                −
            </button>
            <span class="w-8 text-center text-sm font-bold text-slate-900">
                {{ item.quantity }}
            </span>
            <button @click="emit('increment')"
                class="w-8 h-8 bg-slate-100 rounded-lg hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center">
                +
            </button>
            <button @click="emit('remove')" class="ml-1 text-slate-400 hover:text-red-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</template>