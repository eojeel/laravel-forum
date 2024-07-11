<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    href: {
        required: true
    },
    filled: {
        default: false
    }
});
</script>

<template>
    <Link
        :href="href"
        class="whitespace-nowrap mb-2 rounded-full py-0.5 px-2 border border-gray-500 text-emerald-500 hover:bg-gray-500 hover:text-white"
        :class="{ 'bg-gray-500 text-white' : filled }">
        <slot></slot>
    </Link>
</template>
