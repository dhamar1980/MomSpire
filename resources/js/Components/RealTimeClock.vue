<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    showDate: {
        type: Boolean,
        default: true
    },
    showSeconds: {
        type: Boolean,
        default: true
    },
    size: {
        type: String,
        default: 'md' // sm, md, lg
    }
});

const timeString = ref('');
const dateString = ref('');
let intervalId = null;

const sizeClasses = {
    sm: 'text-lg',
    md: 'text-xl',
    lg: 'text-3xl'
};

const updateTime = () => {
    const now = new Date();

    // WIB (Asia/Jakarta)
    const timeOptions = {
        timeZone: 'Asia/Jakarta',
        hour: '2-digit',
        minute: '2-digit',
        second: props.showSeconds ? '2-digit' : undefined,
        hour12: false
    };

    const dateOptions = {
        timeZone: 'Asia/Jakarta',
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };

    timeString.value = now.toLocaleTimeString('id-ID', timeOptions);
    dateString.value = now.toLocaleDateString('id-ID', dateOptions);
};

onMounted(() => {
    updateTime();
    intervalId = setInterval(updateTime, 1000);
});

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId);
});
</script>

<template>
    <div class="text-center">
        <div :class="['font-bold text-blue-600 tracking-wide', sizeClasses[size]]">
            {{ timeString }}
        </div>
        <div v-if="showDate" class="text-sm text-gray-600 mt-1">
            {{ dateString }}
        </div>
        <div class="text-xs text-gray-400 mt-1">
            WIB (Asia/Jakarta)
        </div>
    </div>
</template>