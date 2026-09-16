<script setup lang="ts">
import CartItem from './CartItem.vue';
import type { CartItem as CartItemType } from '@/types/pos';

defineProps<{
    cart: CartItemType[];
    totalItems: number;
}>();

const emit = defineEmits<{
    increment: [index: number];
    decrement: [index: number];
    remove: [index: number];
    clear: [];
}>();
</script>

<template>
    <div class="p-4 border-b border-slate-200 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <h2 class="text-xl font-bold text-slate-900">Carrito</h2>
            <span v-if="totalItems > 0" class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">
                {{ totalItems }}
            </span>
        </div>
        <button v-if="cart.length > 0" @click="emit('clear')"
            class="text-sm text-red-500 hover:text-red-700 font-medium">
            Cancelar (Esc)
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-2 bg-slate-50">
        <CartItem v-for="(item, index) in cart" :key="item.product.id" :item="item"
            @decrement="emit('decrement', index)" @increment="emit('increment', index)"
            @remove="emit('remove', index)" />

        <div v-if="cart.length === 0" class="flex flex-col items-center justify-center py-16 text-slate-400">
            <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-sm">Carrito vacío</span>
        </div>
    </div>

    <slot />
</template>