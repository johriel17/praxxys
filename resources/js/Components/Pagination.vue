<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <div
        v-if="data.links.length > 3"
        class="flex justify-center mt-4 space-x-4"
    >
        <Link
        as="button"
            v-for="(link, k) in data.links"
            :key="k"
            class="px-4 py-3 text-sm leading-4 rounded hover:bg-white focus:text-indigo-500 hover:shadow"
            :class="{ 'bg-primary text-white': link.active}"
            :href="link.url"
            :disabled="link.url === null"
            v-html="link.label"
        />
    </div>
</template>
