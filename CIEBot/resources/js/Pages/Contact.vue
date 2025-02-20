<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>
<template>
    <AppLayout title="Contact"  @theme-changed="updateSelectedTheme">
        <div
            class="flex items-center justify-center mt-5 sm:mt-16 md:mt-20 lg:mt-10 px-4 md:px-56 animate-fade-in-down p-5"
        >
            <div
                class="bg-gray-200 h-auto w-full rounded-xl shadow-2xl flex-col flex items-center justify-center"
                :class="['theme-container', selectedTheme]"
            >
                <h1
                    class="text-3xl sm:text-4xl font-bold text-center mb-3 md:mt-8 my-5"
                >
                    CONTACT
                </h1>

                <div class="flex md:flex-row gap-10 mt-8 mb-5 flex-col">
                    <div class="flex gap-2">
                        <img
                            src="/images/Aldaya.jpg"
                            style="height: 5rem; width: 5rem"
                            class="rounded-full"
                        />
                        <div class="flex flex-col">
                            <h2 class="text-xl sm:text-3xl font-bold">
                                Niky Jane A. Aldaya
                            </h2>
                            <p class="font-bold">nikyjane.aldaya@tup.edu.ph</p>
                            <p class="font-bold">0960427854</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <img
                            src="/images/Domingoo.png"
                            style="height: 5rem; width: 5rem"
                            class="rounded-full"
                        />
                        <div class="flex flex-col">
                            <h2 class="text-xl sm:text-3xl font-bold">
                                Reisha S. Domingo
                            </h2>
                            <p class="font-bold">reisha.domingo@tup.edu.ph</p>
                            <p class="font-bold">09477949216</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 my-5">
                    <img
                        src="/images/Reynon.jpg"
                        style="height: 5rem; width: 5rem"
                        class="rounded-full"
                    />
                    <div class="flex flex-col">
                        <h2 class="text-xl sm:text-3xl font-bold">
                            Niño Jericho S. Reynon
                        </h2>
                        <p class="font-bold">ninojericho.reynon@tup.edu.ph</p>
                        <p class="font-bold">09194097322</p>
                    </div>
                </div>
                <p
                    class="font-bold text-lg sm:text-xl mt-5 text-center px-10 pb-8"
                >
                    We, The researchers who conducted a study entitled CIEBOT: A
                    Chatbot for Enhancing Student Engagement in Information and
                    Communication Technology Essentials. We're dedicated to
                    create a software that meets a goal to foster an environment
                    that values continuous learning and engagement of the
                    students
                </p>
                <!-- <div class="mx-auto md:my-5 my-3">
                    <ApplicationLogo
                        imgSource="/images/Chatbot.png"
                        imgStyle="height: 4rem; width: 4rem"
                    />
                </div> -->
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
