import {reactive, readonly } from 'vue';

const globalState = reactive({
    show: false,
    title: '',
    message: '',
    resolver: null,
});


export function useConfirm() {

    const resetModal = () => {
        globalState.show = false;
        globalState.title = '';
        globalState.message = '';
        globalState.resolver = null;

    }

    return {
        state: readonly(globalState),
        confirmation: (message) => {
            globalState.show = true;
            globalState.title = 'Are you sure?';
            globalState.message = message;

            return new Promise((resolver) => {
                globalState.resolver = resolver;
            });
        },
        confirm: () => {
            globalState.show = false;
            globalState.resolver(true);

            resetModal();

        },
        cancel: () => {
            globalState.show = false;
            globalState.resolver(false);

            resetModal();
        },

    }
}
