<script setup>
import {EditorContent, useEditor} from "@tiptap/vue-3";
import {StarterKit} from "@tiptap/starter-kit";
import {watch} from "vue";
import {Markdown} from "tiptap-markdown";
import MenuButton from "@/Components/MenuButton.vue";
import {Link} from "@tiptap/extension-link";
import {Placeholder} from "@tiptap/extension-placeholder";

const props = defineProps({
    modelValue: '',
    editorClass: '',
    placeholder: null,
})

const model = defineModel();

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [2, 3, 4],
            },
            code: false,
            codeBlock: false,

        }),
        Link,
        Markdown,
        Placeholder.configure({
            placeholder: props.placeholder
        })
    ],
    editorProps: {
        attributes: {
            class: `prose prose-sm max-w-none py-1.5 px-3 ${props.editorClass}`,
        },
    },
    onUpdate: () =>
        model.value = editor.value?.storage.markdown.getMarkdown(),
});

defineExpose({focus: () => editor.value.commands.focus()})

onMounted(() => {
    watch(() =>model, (value) => {
        if (value === editor.value?.storage.markdown.getMarkdown())
        {
            return;
        }
        editor.value?.commands.setContent(value);
    }, {immediate: true});
});

const propmtUserForHref = () => {

    if(editor.value.isActive('link'))
    {
        return editor.value?.chain().unsetLink().run();
    }
    const href = prompt('Enter the URL');
    if (!href)
    {
        return editor.value?.chain().focus().run();
    }
    return editor.value?.chain().focus().setLink({href}).run();
}
</script>


<template>
    <div v-if="editor" class="bg-white rounded-md border-0 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
    <menu class="flex divide-x border-b">
        <MenuButton :editor="editor" @click="() => editor.chain().toggleBold().focus().run()" element="bold" icon="ri-bold" />
        <MenuButton :editor="editor" @click="() => editor.chain().toggleItalic().focus().run()" method="" element="italic" icon="ri-italic" />
        <MenuButton :editor="editor" @click="() => editor.chain().toggleStrike().focus().run()" element="strike" icon="ri-strikethrough" />
        <MenuButton :editor="editor" @click="() => editor.chain().toggleBlockquote().focus().run()" element="blockquote" icon="ri-double-quotes-l" />
        <MenuButton :editor="editor" @click="() => editor.chain().toggleBulletList().focus().run()" element="bulletList" icon="ri-list-unordered" />
        <MenuButton :editor="editor" @click="() => editor.chain().toggleOrderedList().focus().run()" element="orderedList" icon="ri-list-ordered" />

        <li>
            <button
                @click="() => propmtUserForHref()"
                type="button"
                class="px-3 py-2"
                :class="[editor.isActive('link') ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']">
                <i class="ri-link"></i>
            </button>
        </li>

        <li>
            <button
                @click="() => editor.chain().toggleHeading({level: 2}).focus().run()"
                type="button"
                class="px-3 py-2"
                :class="[editor.isActive('heading', { level: 2}) ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']">
                <i class="ri-h-1"></i>
            </button>
        </li>
        <li>
            <button
                @click="() => editor.chain().toggleHeading({level: 3}).focus().run()"
                type="button"
                class="px-3 py-2"
                :class="[editor.isActive('heading', { level: 3}) ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']">
                <i class="ri-h-2"></i>
            </button>
        </li>
        <li>
            <button
                @click="() => editor.chain().toggleHeading({level: 4}).focus().run()"
                type="button"
                class="px-3 py-2"
                :class="[editor.isActive('heading', { level: 4}) ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']">
                <i class="ri-h-3"></i>
            </button>
        </li>
        <slot name="toolbar" :editor="editor"></slot>
    </menu>
        <EditorContent :editor="editor" />
</div>
</template>
<style scoped>
:deep(.tiptap p.is-editor-empty:first-child::before) {
    @apply text-gray-400 float-left h-0 pointer-events-none;
    content: attr(data-placeholder);
}
</style>
