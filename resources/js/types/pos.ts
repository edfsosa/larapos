export interface Category {
    id: number;
    name: string;
    color: string | null;
}

export interface Product {
    id: number;
    name: string;
    sku: string;
    barcode: string | null;
    price: number;
    stock: number;
    category?: Category;
}

export interface CartItem {
    product: Product;
    quantity: number;
}

export interface SaleResult {
    reference: string;
    total: number;
    change: number | null;
    amount_received: number | null;
    payment_method: string;
    items: {
        quantity: number;
        unit_price: number;
        subtotal: number;
        product: { name: string };
    }[];
}

export type PaymentMethod = "cash" | "card";
