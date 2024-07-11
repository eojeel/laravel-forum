<script setup>
import 'remixicon/fonts/remixicon.css'

defineProps({
    editor: {
        type: Object,
        required: true,
    },
    element: String,
    icon: String,
});
</script>
<template>
    <li>
        <button
                type="button"
                class="px-3 py-2"
                :class="[editor.isActive(element) ? 'bg-emerald-500 text-white' : 'hover:bg-gray-200']">
            <i class :class="icon"></i>
        </button>
    </li>
</template>
