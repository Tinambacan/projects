<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import FrequentlyAskedQuestion from "@/Components/FrequentlyAskedQuestion.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <AppLayout @theme-changed="updateSelectedTheme">
        <Head title="Landing Page" />

        <div
            class="flex flex-col md:flex-row justify-between mt-8 md:mt-24 mx-4 md:mx-40"
        >
            <div class="flex flex-col animate-no-fade-right">
                <ApplicationLogo
                    imgSource="/images/Chatbot.png"
                    imgStyle="height: 15rem; width: 15rem"
                />
                <div
                    class="flex items-center justify-center md:mt-0 animate-fade-in-up"
                >
                    <Link :href="route('chatbot')">
                        <button
                            class="inline-flex items-center px-4 py-2 bg-red-950 border border-transparent rounded-md font-semibold text-2xl md:text-4xl text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Get Started
                        </button>
                    </Link>
                </div>
            </div>
            <div
                class="bg-zinc-200 rounded-r-xl rounded-l-xl shadow-2xl mt-20 md:mt-0 md:w-1/2 animate-no-fade-left"
            >
                <div
                    class="text-white text-base md:text-lg font-bold text-center rounded-t-xl p-3 uppercase tracking-wider bg-red-900"
                    :class="['theme-container', selectedTheme]"
                >
                    Frequently Asked Questions
                </div>

                <div class="h-64 md:h-96 overflow-auto">
                    <FrequentlyAskedQuestion />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
export default {
    components: {
        AppLayout,
    },
    data() {
        return {
            selectedTheme: this.getStoredTheme() || 'theme1',
        };
    },
    methods: {
        updateSelectedTheme(theme) {
            this.selectedTheme = theme;
        },

        getStoredTheme() {
            return localStorage.getItem("currentTheme") || 'theme1';
        },
    },
};
</script>

<style>
.theme-container {
    transition: background-color 0.3s;
}

.theme1 {
    background-color: #ffffff;
    border-color: #4d4d4d;
    color: #4d4d4d;
}

.theme2 {
    background-color: #f2ecbe;
    border-color: #c08261;
    color: #9a3b3b;
}

.theme3 {
    background-color: #393646;
    border-color: #f4eee0;
    color: #f4eee0;
}
.theme4 {
    background-color: #eee2de;
    border-color: #2b2a4c;
    color: #2b2a4c;
}
.gen-bg {
    background-color: #ffffff;
}
</style>
