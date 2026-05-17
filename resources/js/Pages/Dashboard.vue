<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import RealTimeClock from '@/Components/RealTimeClock.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Real-Time Clock Widget -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">🕐 Jam Real-Time</h3>
                    <RealTimeClock size="lg" />
                </div>

            </div>
        </div>
    </AppLayout>
</template>