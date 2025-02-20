<script setup>
import { defineProps } from "vue";

const props = defineProps({
    imgSource: String,
    imgStyle: String,
});
</script>

<template>
    <div>
        <img
            class="mx-auto drop-shadow-2xl mb-1"
            :src="imgSource"
            :style="imgStyle"
        />
    </div>
</template>
