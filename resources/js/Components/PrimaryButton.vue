<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg font-semibold text-sm text-white bg-[#d6336c] hover:bg-[#b52a5a] focus:bg-[#b52a5a] active:bg-[#9f244f] focus:outline-none focus:ring-4 focus:ring-[#d6336c]/20 disabled:opacity-50 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
