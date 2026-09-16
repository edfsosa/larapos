<script setup lang="ts">
import { ref, watch } from 'vue';
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
</script>

<template>
    <div class="h-screen flex bg-slate-100 overflow-hidden font-sans">
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

        <SaleTicketModal :sale="pos.lastSale.value" @close="pos.closeTicket" />
    </div>
</template>