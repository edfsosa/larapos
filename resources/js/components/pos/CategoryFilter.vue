<script setup lang="ts">
import type { Category } from '@/types/pos';

defineProps<{
    categories: Category[];
    selected: number | null;
}>();

const emit = defineEmits<{
    select: [id: number | null];
}>();
</script>

<template>
    <div class="mt-3 flex gap-2 overflow-x-auto pb-1">
        <button @click="emit('select', null)" :class="[
            'px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition',
            selected === null
                ? 'bg-blue-600 text-white shadow'
                : 'bg-white text-slate-700 hover:bg-slate-50 shadow-sm',
        ]">
            Todas
        </button>
        <button v-for="cat in categories" :key="cat.id" @click="emit('select', cat.id)" :class="[
            'px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition',
            selected === cat.id
                ? 'bg-blue-600 text-white shadow'
                : 'bg-white text-slate-700 hover:bg-slate-50 shadow-sm',
        ]">
            {{ cat.name }}
        </button>
    </div>
</template>