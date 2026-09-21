<script setup lang="ts">
import { formatGs } from '@/lib/money';
import type { PaymentMethod } from '@/types/pos';

defineProps<{
    total: number;
    change: number;
    paymentMethod: PaymentMethod;
    amountReceived: number;
    loading: boolean;
    error: string | null;
    canCheckout: boolean;
}>();

const emit = defineEmits<{
    'update:paymentMethod': [value: PaymentMethod];
    'update:amountReceived': [value: number];
    quick: [amount: number];
    checkout: [];
}>();

const quickAmounts = [5000, 10000, 20000, 50000, 100000];
</script>

<template>
    <div class="p-4 border-t border-slate-200 space-y-3 bg-white">
        <div class="flex justify-between items-baseline">
            <span class="text-lg font-semibold text-slate-700">Total</span>
            <span class="text-3xl font-bold text-blue-600 whitespace-nowrap">
                {{ formatGs(total) }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-2">
            <button @click="emit('update:paymentMethod', 'cash')" :class="[
                'py-2.5 rounded-lg font-semibold transition',
                paymentMethod === 'cash'
                    ? 'bg-blue-600 text-white shadow'
                    : 'bg-slate-100 text-slate-700 hover:bg-slate-200',
            ]">
                Efectivo
            </button>
            <button @click="emit('update:paymentMethod', 'card')" :class="[
                'py-2.5 rounded-lg font-semibold transition',
                paymentMethod === 'card'
                    ? 'bg-blue-600 text-white shadow'
                    : 'bg-slate-100 text-slate-700 hover:bg-slate-200',
            ]">
                Tarjeta
            </button>
        </div>

        <div v-if="paymentMethod === 'cash'" class="space-y-2">
            <input id="pos-amount-received" name="amount-received" autocomplete="off" :value="amountReceived"
                @input="emit('update:amountReceived', Number(($event.target as HTMLInputElement).value))" type="number"
                step="1000" min="0" placeholder="Monto recibido"
                class="w-full p-3 border-2 border-slate-300 rounded-lg text-lg font-medium focus:border-blue-500 outline-none" />

            <div class="grid grid-cols-5 gap-1.5">
                <button v-for="amount in quickAmounts" :key="amount" @click="emit('quick', amount)"
                    class="py-2 text-xs font-semibold bg-slate-100 hover:bg-slate-200 rounded text-slate-700">
                    +{{ (amount / 1000).toFixed(0) }}K
                </button>
            </div>

            <div class="flex justify-between items-center pt-1">
                <span class="text-sm text-slate-600">Cambio:</span>
                <span class="text-xl font-bold text-emerald-600 whitespace-nowrap">
                    {{ formatGs(change) }}
                </span>
            </div>
        </div>

        <div v-if="error" class="text-sm bg-red-50 border border-red-200 text-red-700 p-2.5 rounded-lg">
            {{ error }}
        </div>

        <button @click="emit('checkout')" :disabled="loading || !canCheckout"
            class="w-full py-4 bg-emerald-600 text-white rounded-lg text-lg font-bold hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg transition">
            {{ loading ? 'Procesando...' : 'Cobrar (F4)' }}
        </button>
    </div>
</template>