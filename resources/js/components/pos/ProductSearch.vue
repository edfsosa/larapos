<script setup lang="ts">
import { ref } from 'vue';

const model = defineModel<string>({ required: true });
const emit = defineEmits<{ enter: [] }>();

const inputEl = ref<HTMLInputElement>();

function focus() {
    inputEl.value?.focus();
}

defineExpose({ focus });
</script>

<template>
    <div class="relative">
        <input ref="inputEl" v-model="model" @keydown.enter.prevent="emit('enter')"
            placeholder="Buscar producto, SKU o escanear código... (F2)"
            class="w-full p-4 pl-12 text-lg rounded-xl border-2 border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none bg-white shadow-sm" />
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>
</template>