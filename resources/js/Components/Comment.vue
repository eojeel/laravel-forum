
<script setup>
import {relativeDate} from "@/Utilities/date.js";
import {Link} from "@inertiajs/vue3";
import {HandThumbDownIcon, HandThumbUpIcon} from "@heroicons/vue/16/solid/index.js";

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
            <span class="first-letter:uppercase block pt-1 text-xs text-gray-600">By {{ comment.user.name }} {{ relativeDate(comment.created_at) }} |
                <span class="text-gray-500 font-bold">
                    {{ comment.likes_count }} Likes
                </span></span>
            <div class="mt-2 empty:hidden flex justify-start space-x-3">

                <div v-if="$page.props.auth.user">
                    <Link v-if="comment.can.like" preserve-scroll :href="route('likes.store', ['comment', comment.id])" method="post" class="inline-block hover:text-gray-500 transition-colors text-gray-700">
                        <HandThumbUpIcon class="size-4 inline-block"/>
                        <span class="sr-only">Like Comment</span>
                    </Link>
                    <Link v-else preserve-scroll :href="route('likes.destroy', ['comment', comment.id])" method="delete" class="inline-block hover:text-gray-500 transition-colors text-gray-700">
                        <HandThumbDownIcon class="size-4 inline-block"/>
                        <span class="sr-only">Unlike Comment</span>
                    </Link>
                </div>

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
