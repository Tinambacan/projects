<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>
<template>
    <AppLayout title="About" @theme-changed="updateSelectedTheme">
        <div
            class="flex items-center justify-center mt-10 sm:mt-16 md:mt-20 lg:mt-28 px-4 md:px-56 animate-fade-in-down"
        >
            <div
                class="h-auto md:h-96 w-full bg-white rounded-xl shadow-2xl flex-col flex items-center justify-center"
                :class="['theme-container', selectedTheme]"
            >
                <h1
                    class=" text-3xl sm:text-4xl font-bold text-center mb-3 md:mt-8 my-5"
                >
                    ABOUT
                </h1>
                <h2
                    class=" text-lg sm:text-3xl font-bold text-center mx-2 sm:mx-10 my-3 sm:my-5"
                >
                    We're changing the way you connect with technology and
                    obtain information about the College of Industrial Education
                    by introducing you to CIEBOT, a chatbot system. Your life
                    will be made simpler, more effective, and more engaging
                    thanks to our chatbot system. Discover more about us.
                </h2>
                <div class="mx-auto md:my-5 my-3">
                    <ApplicationLogo
                        imgSource="/images/Chatbot.png"
                        imgStyle="height: 4rem; width: 4rem"
                    />
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
            selectedTheme:  this.getStoredTheme() || 'theme1',
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
    transition: background-color 0.5s ease-in-out;
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
