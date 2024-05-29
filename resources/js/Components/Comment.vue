
<script setup>
import {relativeDate} from "@/Utilities/date.js";

const props = defineProps(['comment']);

const emit = defineEmits(['delete','edit']);
</script>

<template>
    <div class="sm:flex py-3 border-b-2">
        <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
            <img :src="comment.user.profile_photo_url" class="h-10 w-10 rounded-full"/>
        </div>
        <div class="flex-1">
            <div class="mt-1 prose prose-sm max-w-none" v-html="comment.html"></div>
            <span class="first-letter:uppercase block pt-1 text-xs text-gray-600">By {{ comment.user.name }} {{ relativeDate(comment.created_at) }} ago</span>
            <div class="mt-2 empty:hidden flex justify-start space-x-3">
                <form v-if="comment.can?.delete" @submit.prevent="$emit('delete', comment.id)">
                    <button class="text-xs text-red-500">Delete</button>
                </form>
                <form v-if="comment.can?.edit" @submit.prevent="$emit('edit', comment.id)">
                    <button class="text-xs text-orange-500">Edit</button>
                </form>
            </div>
        </div>
    </div>
</template>
