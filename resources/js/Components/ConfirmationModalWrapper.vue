<script setup>
import confirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useConfirm} from "@/Utilities/Composables/useConfirm.js";
import {ref, nextTick, watchEffect} from "vue";

const {state, confirm,  cancel} = useConfirm();

const cancelButtonRef = ref(null);

watchEffect(async() => {
    if(state.show)
    {
        await nextTick();
        cancelButtonRef.value?.$el.focus();

    }
})
</script>

<template>
<confirmation-modal :show="state.show">
    <template #title>
        {{ state.title }}
    </template>
    <template #content>
        <p>{{ state.message }}</p>
    </template>
    <template #footer>
        <SecondaryButton ref="cancelButtonRef" @click="cancel" class="mr-2">Cancel</SecondaryButton>
        <PrimaryButton @click="confirm">Confirm</PrimaryButton>
    </template>
</confirmation-modal>
</template>
