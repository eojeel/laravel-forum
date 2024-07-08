<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import {relativeDate} from "@/Utilities/date.js";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";

const formattedDate = (date) => relativeDate(date);

defineProps(['posts', 'selectedTopic', 'topics']);
</script>

<template>
    <AppLayout>
        <container>
            <div>
            <Link v-if="selectedTopic" :href="route('posts.index')" class="text-indigo-500 hover:text-indigo-700 block mb-2">Back to all Posts</Link>
                <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All Posts'"></PageHeading>
                <p v-if="selectedTopic" class="mt-1 text-gray-600 text-sm">{{ selectedTopic.description }}</p>
                <menu class="flex space-x-1 mt-4 overflow-x-auto pb-2 pt-1">
                    <li v-for="topic in topics" :key="topic.id">
                        <Pill :href="route('posts.index', {topic: topic.slug})" :filled="topic.id === selectedTopic?.id">
                            {{ topic.name }}
                        </Pill>
                    </li>
                </menu>
            </div>
            <ul class="divide-y mt-4">
                <li v-for="post in posts.data" :key="post.id" class="flex justify-between items-baseline flex-col md:flex-row">
                    <Link :href="post.routes.show"  class="block group px-2 py-4">
                        <span class="bold text-lg group-hover:text-emerald-500">{{ post.title }}</span>
                        <span class="block mt-2 text-sm text-black">by {{ post.user.name }} {{ formattedDate(post.created_at) }}</span>
                    </Link>
                    <Pill :href="route('posts.index', {topic: post.topic.slug})">
                        {{ post.topic.name }}
                    </Pill>
                </li>
            </ul>
        </container>
        <Pagination :meta="posts.meta" />
    </AppLayout>
</template>
