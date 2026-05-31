<template>
    <div class="mom-auth-page">
        <div class="w-full max-w-[420px]">
            <div class="flex justify-center">
                <slot name="logo" />
            </div>

            <div class="mom-auth-card mt-4">
                <slot />
            </div>
        </div>
    </div>
</template>
