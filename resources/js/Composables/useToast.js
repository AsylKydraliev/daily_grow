import { ref } from 'vue';

const toasts = ref([]);
let toastIdCounter = 0;

export const useToast = () => {
    const show = (message, type = 'success', duration = 5000) => {
        const id = `toast-${++toastIdCounter}`;
        const toast = {
            id,
            message,
            type,
            duration
        };

        toasts.value.push(toast);

        return id;
    };

    const success = (message, duration = 5000) => {
        return show(message, 'success', duration);
    };

    const error = (message, duration = 5000) => {
        return show(message, 'error', duration);
    };

    const warning = (message, duration = 5000) => {
        return show(message, 'warning', duration);
    };

    const info = (message, duration = 5000) => {
        return show(message, 'info', duration);
    };

    const remove = (id) => {
        const index = toasts.value.findIndex(toast => toast.id === id);
        if (index > -1) {
            toasts.value.splice(index, 1);
        }
    };

    const clear = () => {
        toasts.value = [];
    };

    return {
        toasts,
        show,
        success,
        error,
        warning,
        info,
        remove,
        clear
    };
};

