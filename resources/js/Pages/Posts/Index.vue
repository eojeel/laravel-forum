<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import {relativeDate} from "@/Utilities/date.js";

const formattedDate = (date) => relativeDate(date);

defineProps({
    posts: {
        type: Array,
        required: true
    }
})
</script>

<template>
    <AppLayout>
        <container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id">
                    <Link :href="post.routes.show"  class="block group px-2 py-4">
                        <span class="bold text-lg group-hover:text-emerald-500">{{ post.title }}</span>
                        <span class="block mt-2 text-sm text-black">by {{ post.user.name }} {{ formattedDate(post.created_at) }} ago</span>
                    </Link>
                </li>
            </ul>
        </container>
        <Pagination :meta="posts.meta" />
    </AppLayout>
</template>
