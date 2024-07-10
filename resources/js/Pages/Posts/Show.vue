<script setup>
import {ref, computed} from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from "@/Components/Pagination.vue";
import {relativeDate} from "@/Utilities/date.js";
import Comment from "@/Components/Comment.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {Head, router, useForm, Link} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useConfirm} from "@/Utilities/Composables/useConfirm.js";
import MarkDownEditor from "@/Components/MarkDownEditor.vue";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";
import { HandThumbUpIcon, HandThumbDownIcon } from "@heroicons/vue/16/solid/index.js";

const props = defineProps(['post', 'comments']);
const formattedDate = (date) => relativeDate(date);
const commentTextArea = ref(null);
const commentBeingEdited = ref(null);

const { confirmation } = useConfirm();

const commentForm = useForm({
    body: ''
});

const AddComment = () => {
    commentForm.post(route('posts.comments.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),
    });
}

const commentEditing = computed(() => props.comments.data.find(comment => comment.id === commentBeingEdited.value))
const editComment = (commentId) => {
    commentBeingEdited.value = commentId;
    commentForm.body = commentEditing.value?.body;
    commentTextArea.value?.focus();
}

const cancelEditComment = () => {
    commentBeingEdited.value = null;
    commentForm.reset();
}

const deleteComment = async (commentId) => {
    if(! await confirmation('Are you sure you want to delete the comment!'))
    {
        return;
    }

    router.delete(route('comment.destroy', {comment: commentId, page: props.comments.meta.current_page}), {
        preserveScroll: true,
    });
}

const UpdateComment = async () => {

    if(! await confirmation('Are you sure you want to update the comment!'))cd
    {
        commentTextArea.value?.focus();
        return;
    }

    commentForm.put(route('comments.update', {
        comment: commentEditing.value.id,
        page: props.comments.data.length > 1 ? props.comments.meta.current_page : Math.max(props.comments.meta.current_page -1, 1)
    }), {
        preserveScroll: true,
        onSuccess: () => {
            cancelEditComment();
        }
    });
}
</script>

<template>
    <Head>
        <link rel="canonical" :href="post.routes.show" />
    </Head>

    <AppLayout :title="post.title">
        <container>
            <PageHeading>{{ post.title }}</PageHeading>
            <Pill :href="route('posts.index', {topic: post.topic.slug })">{{ post.topic.name }}</Pill>
            <span class="block mt-2 text-sm text-gray-700">{{ formattedDate(post.created_at) }} ago by {{post.user.name}}</span>
            <div class="mt-4">
                <span class="text-pink-500 font-bold">
                    {{ post.likes_count }} Likes
                </span>
                <div class="mt-2" v-if="$page.props.auth.user">
                    <Link v-if="post.can.like" :href="route('likes.store', ['post', post.id])" method="post" class="inline-block bg-indigo-600 hover:bg-pink-500 transition-colors text-white py-1.5 px-3 rounded-full">
                        <HandThumbUpIcon class="size-4 inline-block"/>
                        Like Post
                    </Link>
                    <Link v-else :href="route('likes.destroy', ['post', post.id])" method="delete" class="inline-block bg-indigo-600 hover:bg-pink-500 transition-colors text-white py-1.5 px-3 rounded-full">
                        <HandThumbDownIcon class="size-4 inline-block"/>
                        Unlike Post
                    </Link>
                </div>
            </div>
            <article class="mt-4 prose prose-sm max-w-none" v-html="post.html">
            </article>

            <div class="mt-10">

                <form v-if="$page.props.auth.user"
                      @submit.prevent="() => commentBeingEdited ? UpdateComment() : AddComment()" class="mt-4">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <MarkDownEditor ref="commentTextArea" id="body" v-model="commentForm.body" placeholder="Comment" editorClass="min-h-[160px]"/>
                        <input-error :message="commentForm.errors.body" class="mt-2"></input-error>
                    </div>
                    <PrimaryButton type="submit" class="mt-4" :disabled="commentForm.processing"
                                   v-text="commentBeingEdited ? 'Update Comment' : 'Add Comment'"></PrimaryButton>
                    <SecondaryButton @click="cancelEditComment" v-if="commentBeingEdited" class="ml-2">Cancel
                    </SecondaryButton>
                </form>

                <h2 class="text-xl font-semibold mt-4">Comments</h2>
                <ul>
                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4 break-all">
                        <Comment @delete="deleteComment" @edit="editComment" :comment="comment"/>
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']"></Pagination>
            </div>
        </container>
    </AppLayout>
</template>
