<script setup>
import { defineProps } from "vue";

const props = defineProps({
    type: {
        type: String,
    },
    href: String,
});
</script>

<template>
    <!-- <a
        v-if="href"
        :href="href"
        :class="getButtonClass()"
        @click.prevent="$emit('click')"
    >
        <slot />
    </a> -->
    <Link v-if="href" :href="href" :class="classes">
        <slot />
    </Link>
    <button
        v-else
        :type="type"
        :class="getButtonClass()"
        @click.prevent="$emit('click')"
    >
        <slot />
    </button>
</template>

<style scoped>
/* Add your styles here */
</style>
