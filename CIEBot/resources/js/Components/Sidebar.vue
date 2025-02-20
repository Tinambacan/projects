<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import ApplicationMark from "./ApplicationMark.vue";

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? "inline-flex items-center px-1 pt-1 text-indigo-800 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:text-indigo-700 transition duration-150 ease-in-out"
        : "inline-flex items-center px-1 pt-1  border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out";
});
</script>

<template>
    <!-- <Link :href="href" :class="classes">
        <slot />
    </Link> -->

    <div
        class="bg-red-400 left-0 transition duration-1000 ease-in-out min-h-screen relative w-56"
    >
        <div class="fixed">
            <div class="shrink-0 flex items-center justify-center">
                <Link :href="route('browse')">
                    <ApplicationMark class="block h-9 w-auto" />
                </Link>
            </div>
        </div>
    </div>
</template>
