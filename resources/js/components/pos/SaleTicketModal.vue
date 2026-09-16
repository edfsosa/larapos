<script setup lang="ts">
import { formatGs } from '@/lib/money';
import type { SaleResult } from '@/types/pos';

defineProps<{ sale: SaleResult | null }>();
const emit = defineEmits<{ close: [] }>();
</script>

<template>
    <div v-if="sale" class="fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50"
        @click.self="emit('close')">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4">
            <div class="text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-9 h-9 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">
                    Venta registrada
                </h3>
                <p class="text-sm text-slate-500 mt-1">
                    Folio: {{ sale.reference }}
                </p>
            </div>

            <div class="bg-slate-50 rounded-lg p-4 space-y-2 text-sm">
                <div v-for="(item, i) in sale.items" :key="i" class="flex justify-between text-slate-700">
                    <span class="truncate">
                        {{ item.quantity }}× {{ item.product.name }}
                    </span>
                    <span class="whitespace-nowrap ml-2">
                        {{ formatGs(item.subtotal) }}
                    </span>
                </div>
            </div>

            <div class="border-t pt-3 space-y-1.5">
                <div class="flex justify-between text-sm text-slate-600">
                    <span>Total</span>
                    <span class="font-semibold text-slate-900">
                        {{ formatGs(sale.total) }}
                    </span>
                </div>
                <div v-if="sale.amount_received" class="flex justify-between text-sm text-slate-600">
                    <span>Recibido</span>
                    <span>{{ formatGs(sale.amount_received) }}</span>
                </div>
                <div v-if="sale.change !== null" class="flex justify-between text-lg font-bold">
                    <span class="text-slate-700">Cambio</span>
                    <span class="text-emerald-600 whitespace-nowrap">
                        {{ formatGs(sale.change) }}
                    </span>
                </div>
            </div>

            <button @click="emit('close')"
                class="w-full py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition">
                Nueva venta (F4)
            </button>
        </div>
    </div>
</template>