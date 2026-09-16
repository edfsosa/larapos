import { ref, computed, nextTick, onMounted, onBeforeUnmount } from "vue";
import type { Ref } from "vue";
import axios from "axios";
import type {
    CartItem,
    Category,
    PaymentMethod,
    Product,
    SaleResult,
} from "@/types/pos";

interface SearchComponent {
    focus: () => void;
}

export function usePos(searchCompRef?: Ref<SearchComponent | undefined>) {
    const search = ref("");
    const products = ref<Product[]>([]);
    const categories = ref<Category[]>([]);
    const selectedCategory = ref<number | null>(null);
    const cart = ref<CartItem[]>([]);
    const paymentMethod = ref<PaymentMethod>("cash");
    const amountReceived = ref<number>(0);
    const loading = ref(false);
    const loadingProducts = ref(false);
    const error = ref<string | null>(null);
    const lastSale = ref<SaleResult | null>(null);

    let searchTimer: ReturnType<typeof setTimeout> | null = null;

    const total = computed(() =>
        cart.value.reduce((sum, i) => sum + i.product.price * i.quantity, 0)
    );

    const totalItems = computed(() =>
        cart.value.reduce((sum, i) => sum + i.quantity, 0)
    );

    const change = computed(() => {
        if (paymentMethod.value !== "cash") return 0;
        return Math.max(0, amountReceived.value - total.value);
    });

    const filteredProducts = computed(() => {
        if (!selectedCategory.value) return products.value;
        return products.value.filter(
            (p) => p.category?.id === selectedCategory.value
        );
    });

    async function fetchProducts() {
        loadingProducts.value = true;
        try {
            const { data } = await axios.get("/api/pos/products", {
                params: { q: search.value || undefined },
            });
            products.value = data;

            const map = new Map<number, Category>();
            for (const p of data) {
                if (p.category && !map.has(p.category.id)) {
                    map.set(p.category.id, p.category);
                }
            }
            categories.value = Array.from(map.values());
        } finally {
            loadingProducts.value = false;
        }
    }

    function focusSearch() {
        nextTick(() => searchCompRef?.value?.focus());
    }

    function debouncedFetch() {
        if (searchTimer) clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchProducts, 200);
    }

    function addToCart(p: Product) {
        if (p.stock < 1) return;
        const existing = cart.value.find((i) => i.product.id === p.id);
        if (existing) {
            if (existing.quantity + 1 > p.stock) {
                error.value = `Stock máximo alcanzado para ${p.name}`;
                setTimeout(() => (error.value = null), 2500);
                return;
            }
            existing.quantity++;
        } else {
            cart.value.push({ product: p, quantity: 1 });
        }
        search.value = "";
        focusSearch();
    }

    function removeFromCart(index: number) {
        cart.value.splice(index, 1);
    }

    function changeQuantity(index: number, delta: number) {
        const item = cart.value[index];
        const newQty = item.quantity + delta;
        if (newQty < 1) {
            removeFromCart(index);
            return;
        }
        if (newQty > item.product.stock) return;
        item.quantity = newQty;
    }

    function clearCart() {
        if (cart.value.length === 0) return;
        if (confirm("¿Cancelar la venta actual?")) {
            cart.value = [];
            amountReceived.value = 0;
            error.value = null;
        }
    }

    function addQuickAmount(amount: number) {
        amountReceived.value = (amountReceived.value || 0) + amount;
    }

    async function checkout() {
        if (cart.value.length === 0) return;

        if (
            paymentMethod.value === "cash" &&
            amountReceived.value < total.value
        ) {
            error.value = "El monto recibido es menor al total.";
            return;
        }

        loading.value = true;
        error.value = null;

        try {
            const { data } = await axios.post("/api/pos/sales", {
                items: cart.value.map((i) => ({
                    product_id: i.product.id,
                    quantity: i.quantity,
                })),
                payment_method: paymentMethod.value,
                amount_received:
                    paymentMethod.value === "cash"
                        ? amountReceived.value
                        : null,
            });

            lastSale.value = data;
            cart.value = [];
            amountReceived.value = 0;
            await fetchProducts();
            focusSearch();
        } catch (e: any) {
            const errors = e.response?.data?.errors;
            error.value = errors
                ? Object.values(errors).flat().join(" ")
                : e.response?.data?.message || "Error al registrar la venta.";
        } finally {
            loading.value = false;
        }
    }

    function closeTicket() {
        lastSale.value = null;
        focusSearch();
    }

    function onSearchEnter() {
        if (filteredProducts.value.length === 1) {
            addToCart(filteredProducts.value[0]);
        }
    }

    function onKeydown(e: KeyboardEvent) {
        if (e.key === "F2") {
            e.preventDefault();
            focusSearch();
        }
        if (e.key === "F4") {
            e.preventDefault();
            if (lastSale.value) closeTicket();
            else checkout();
        }
        if (e.key === "Escape") {
            if (lastSale.value) closeTicket();
            else clearCart();
        }
    }

    onMounted(() => {
        fetchProducts();
        focusSearch();
        window.addEventListener("keydown", onKeydown);
    });

    onBeforeUnmount(() => {
        window.removeEventListener("keydown", onKeydown);
    });

    return {
        // estado
        search,
        products,
        categories,
        selectedCategory,
        cart,
        paymentMethod,
        amountReceived,
        loading,
        loadingProducts,
        error,
        lastSale,
        // computados
        total,
        totalItems,
        change,
        filteredProducts,
        // acciones
        fetchProducts,
        debouncedFetch,
        addToCart,
        removeFromCart,
        changeQuantity,
        clearCart,
        addQuickAmount,
        checkout,
        closeTicket,
        onSearchEnter,
    };
}
