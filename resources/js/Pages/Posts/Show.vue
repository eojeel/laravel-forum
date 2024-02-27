<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from "@/Components/Pagination.vue";
import {relativeDate} from "@/Utilities/date.js";
import Comment from "@/Components/Comment.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps(['post', 'comments']);
const formattedDate = (date) => relativeDate(date);

const commentForm = useForm({
    body: ''
});

const AddComment = () => {
    commentForm.post(route('posts.comments.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),
    });
}
</script>

<template>
    <AppLayout :title="post.title">
        <container>
            <h1 class="text-2xl font-bold">{{ post.title }}</h1>
            <span class="block mt-2 text-sm text-gray-700">{{ formattedDate(post.created_at) }} ago by {{ post.user.name }}</span>
            <article class="mt-4 ">
                <pre class="whitespace-pre-wrap font-sans">{{ post.body }}</pre>
            </article>

            <div class="mt-10">

                <form v-if="$page.props.auth.user" @submit.prevent="AddComment" class="mt-4">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <TextArea id="body" v-model="commentForm.body" placeholder="Comment"/>
                        <input-error :message="commentForm.errors.body" class="mt-2"></input-error>
                    </div>

                    <PrimaryButton type="submit" class="mt-4" :disabled="commentForm.processing">Add Comment</PrimaryButton>
                </form>


                <h2 class="text-xl font-semibold mt-4">Comments</h2>
                <ul>
                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4 break-all">
                        <Comment :comment="comment" />
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']"></Pagination>
            </div>
        </container>
    </AppLayout>
</template>
