<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import {relativeDate} from "@/Utilities/date.js";
import PageHeading from "@/Components/PageHeading.vue";

const formattedDate = (date) => relativeDate(date);

defineProps(['posts', 'selectedTopic']);
</script>

<template>
    <AppLayout>
        <container>
            <Link :href="route('posts.index')" class="text-indigo-500 hover:text-indigo-700 block mb-2">Back to all Posts</Link>
            <div>
                <PageHeading v-text="selectedTopic ? selectedTopic.name : 'Al Posts'"></PageHeading>
                <p v-if="selectedTopic" class="mt-1 text-gray-600 text-sm">{{ selectedTopic.description }}</p>
            </div>
            <ul class="divide-y mt-4">
                <li v-for="post in posts.data" :key="post.id" class="flex justify-between items-baseline flex-col md:flex-row">
                    <Link :href="post.routes.show"  class="block group px-2 py-4">
                        <span class="bold text-lg group-hover:text-emerald-500">{{ post.title }}</span>
                        <span class="block mt-2 text-sm text-black">by {{ post.user.name }} {{ formattedDate(post.created_at) }}</span>
                    </Link>
                    <Link
                        :href="route('posts.index', {topic: post.topic.slug})"
                        class="mb-2 rounded-full py-0.5 px-2 border borer-pink-500 text-pink-500 hover:bg-indigo-500 hover:text-indigo-50">
                        {{ post.topic.name }}
                    </Link>
                </li>
            </ul>
        </container>
        <Pagination :meta="posts.meta" />
    </AppLayout>
</template>
