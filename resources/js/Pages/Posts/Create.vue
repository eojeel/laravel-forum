<script setup xmlns="http://www.w3.org/1999/html">
import {useForm} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import MarkldownEditor from "@/Components/MarkDownEditor.vue";
import {isInProduction} from "@/Utilities/enviroment.js";
import PageHeading from "@/Components/PageHeading.vue";

const props = defineProps(['topics']);

const form = useForm({
    title: '',
    topic_id: props.topics[0].id,
    body: '',
});

const createPost = () => form.post(route('posts.store'));

const autofill = async () => {
    if(!isInProduction)
    {
        return;
    }
    const response = await axios.get('/local/post-content');

    form.title = response.data.title;
    form.body = response.data.body;
}
</script>

<template>
    <AppLayout title="Create a Post.">
        <container>
            <PageHeading>Create a Post</PageHeading>
            <form @submit.prevent="createPost" class="mt-6">
                <div>
                    <InputLabel for="title" class="sr-only">Title</InputLabel>
                    <textInput id="title" class="w-full" v-model="form.title" placeholder="Give it a great title..."/>
                    <InputError :message="form.errors.title" class="mt-1" />
                </div>
                <div class="mt-3">
                    <InputLabel for="body" class="sr-only">Body</InputLabel>
                    <div>
                        <InputLabel for="topic_id">Select a Topic</InputLabel>
                        <select v-model="form.topic_id" :id="form.topic_id" class="mt-1 mb-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <option v-for="topic in topics" :key="topic.id" :value="topic.id">
                                {{ topic.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.topic_id" class="mt-1" />
                    </div>
                    <MarkldownEditor id="body" v-model="form.body" editorClass="min-h-[512px]">
                        <template #toolbar="{ editor }">
                            <li v-if="!isInProduction()">
                                <button @click="autofill"
                                        type="button"
                                        class="px-3 py-2"
                                        title="Autfill">
                                        <i class="ri-article-line"></i>
                                </button>
                            </li>
                        </template>
                    </MarkldownEditor>
                    <InputError :message="form.errors.body" class="mt-1" />
                </div>
                <div>
                    <primary-button type="submit">Create Post</primary-button>
                </div>
            </form>
        </container>
    </AppLayout>
</template>
