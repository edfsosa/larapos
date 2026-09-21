<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { usePos } from '@/composables/usePos';
import ProductSearch from '@/components/pos/ProductSearch.vue';
import CategoryFilter from '@/components/pos/CategoryFilter.vue';
import ProductGrid from '@/components/pos/ProductGrid.vue';
import CartPanel from '@/components/pos/CartPanel.vue';
import PaymentPanel from '@/components/pos/PaymentPanel.vue';
import SaleTicketModal from '@/components/pos/SaleTicketModal.vue';

const searchComp = ref<InstanceType<typeof ProductSearch>>();
const pos = usePos(searchComp);

watch(pos.search, () => pos.debouncedFetch());

function logout() {
    router.post('/logout');
}
</script>

<template>
    <div class="h-screen flex flex-col bg-slate-100 overflow-hidden font-sans">
        <!-- Header POS -->
        <header class="bg-white border-b border-slate-200 px-4 py-2 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-3">
                <span class="font-bold text-slate-900">POS</span>
                <span class="text-xs text-slate-400">Larapos</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-600">{{ $page.props.auth.user.name }}</span>
                <button @click="logout" class="text-sm text-red-500 hover:text-red-700 font-medium">
                    Cerrar sesión
                </button>
            </div>
        </header>

        <!-- Contenido principal -->
        <div class="flex-1 flex overflow-hidden">
            <div class="flex-1 flex flex-col p-4 min-w-0">
                <ProductSearch ref="searchComp" v-model="pos.search.value" @enter="pos.onSearchEnter" />

                <CategoryFilter v-if="pos.categories.value.length > 0" :categories="pos.categories.value"
                    :selected="pos.selectedCategory.value" @select="pos.selectedCategory.value = $event" />

                <ProductGrid :products="pos.filteredProducts.value" :loading="pos.loadingProducts.value"
                    :search="pos.search.value" @add="pos.addToCart" />
            </div>

            <div class="w-110 bg-white shadow-2xl flex flex-col border-l border-slate-200">
                <CartPanel :cart="pos.cart.value" :total-items="pos.totalItems.value"
                    @increment="(i) => pos.changeQuantity(i, 1)" @decrement="(i) => pos.changeQuantity(i, -1)"
                    @remove="pos.removeFromCart" @clear="pos.clearCart">
                    <PaymentPanel :total="pos.total.value" :change="pos.change.value"
                        :payment-method="pos.paymentMethod.value" :amount-received="pos.amountReceived.value"
                        :loading="pos.loading.value" :error="pos.error.value" :can-checkout="pos.cart.value.length > 0"
                        @update:payment-method="pos.paymentMethod.value = $event"
                        @update:amount-received="pos.amountReceived.value = $event" @quick="pos.addQuickAmount"
                        @checkout="pos.checkout" />
                </CartPanel>
            </div>
        </div>

        <SaleTicketModal :sale="pos.lastSale.value" @close="pos.closeTicket" />
    </div>
</template>