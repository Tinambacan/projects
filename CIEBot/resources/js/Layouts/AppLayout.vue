<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref } from "vue";
import Banner from "@/Components/Banner.vue";
// import TopNavBar from "@/Components/TopNavBar.vue";
import Footer from "@/Components/Footer.vue";
import Topics from "@/Components/Topics.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const showingNavigationDropdown = ref(false);

const props = defineProps(["title"]);
</script>

<template>
    <template v-if="route().current('landingpage')">
        <div>
            <Head :title="title" />

            <Banner />
            <div
                class="min-h-screen theme-transition"
                :style="{
                    'background-image': getBackgroundImage1(),
                }"
                :class="{ 'bg-cover': true }"
            >
                <!-- <TopNavBar /> -->
                <nav class=" ">
                    <!-- Primary Navigation Menu -->
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <!-- <div class="flex"> -->
                            <div
                                class="shrink-0 flex items-center text-white text-4xl"
                            >
                                <Link :href="route('landingpage')">
                                    <!-- <ApplicationLogo imgSource="/images/Chatbot.png" /> -->
                                    CIEBOT
                                </Link>
                            </div>

                            <div class="flex py-auto gap-5">
                                <div
                                    class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                                >
                                    <div
                                        class="my-auto gap-2 text-sm md:ml-0 ml-3 mb-2"
                                    >
                                        <select
                                            v-model="selectedTheme"
                                            @change="changeTheme"
                                            class="rounded-xl text-sm"
                                        >
                                            <option value="theme1">
                                                Default
                                            </option>
                                            <option value="theme2">
                                                Vintage
                                            </option>
                                            <option value="theme3">
                                                Night
                                            </option>
                                            <option value="theme4">
                                                Retro
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div
                                    class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                                >
                                    <NavLink
                                        :href="route('contact')"
                                        :active="route().current('contact')"
                                    >
                                        Contact
                                    </NavLink>
                                </div>
                                <div
                                    class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                                >
                                    <NavLink
                                        :href="route('about')"
                                        :active="route().current('about')"
                                    >
                                        About
                                    </NavLink>
                                </div>
                            </div>

                            <div class="-me-2 flex items-center sm:hidden">
                                <button
                                    class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                    @click="
                                        showingNavigationDropdown =
                                            !showingNavigationDropdown
                                    "
                                >
                                    <svg
                                        class="h-6 w-6"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            :class="{
                                                hidden: showingNavigationDropdown,
                                                'inline-flex':
                                                    !showingNavigationDropdown,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            :class="{
                                                hidden: !showingNavigationDropdown,
                                                'inline-flex':
                                                    showingNavigationDropdown,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div
                        :class="{
                            block: showingNavigationDropdown,
                            hidden: !showingNavigationDropdown,
                        }"
                        class="sm:hidden"
                    >
                        <div class="pt-2 my-auto">
                            <div
                                class="my-auto gap-2 text-sm md:ml-0 ml-3 mb-2"
                            >
                                <select
                                    v-model="selectedTheme"
                                    @change="changeTheme"
                                    class="rounded-xl text-sm"
                                >
                                    <option value="theme1">Default</option>
                                    <option value="theme2">Vintage</option>
                                    <option value="theme3">Night</option>
                                    <option value="theme4">Retro</option>
                                </select>
                            </div>
                            <ResponsiveNavLink
                                :href="route('contact')"
                                :active="route().current('contact')"
                            >
                                CONTACT
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('about')"
                                :active="route().current('about')"
                            >
                                ABOUT
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </nav>

                <!-- BODY -->
                <main>
                    <slot :class="selectedTheme" />
                </main>
            </div>
            <div
                class="min-h-screen theme-transition"
                :style="{
                    'background-image': getBackgroundImage2(),
                }"
                :class="{ 'bg-cover': true }"
            >
                <div class="md:absolute relative">
                    <div
                        class="md:pl-5 md:mt-28 flex flex-col md:flex-row gap-4 md:gap-10"
                    >
                        <div class="rounded-xl w-full mt-10 md:mt-0 p-3 md:p-0">
                            <div
                                class="text-3xl font-bold text-red-900 text-center rounded-xl p-3 uppercase tracking-wider"
                                :class="['theme-container', selectedTheme]"
                            >
                                Topics
                            </div>
                            <div class="overflow-auto h-64 md:h-96">
                                <Topics />
                            </div>
                        </div>
                        <div class="right-0 mt-8 md:mt-0">
                            <img
                                src="images/ict.png"
                                style="md:height: 35rem; width: 75rem"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <Footer />
        </div>
    </template>
    <template
        v-else-if="
            route().current('chatbot') ||
            route().current('about') ||
            route().current('contact')
        "
    >
        <div>
            <Head :title="title" />

            <Banner />
            <div
                class="min-h-screen theme-transition"
                :style="{
                    'background-image': getBackgroundImage1(),
                }"
                :class="{ 'bg-cover': true }"
            >
                <!-- <TopNavBar /> -->
                <nav class=" ">
                    <!-- Primary Navigation Menu -->
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <!-- <div class="flex"> -->
                            <div
                                class="shrink-0 flex items-center text-white text-4xl"
                            >
                                <Link :href="route('landingpage')">
                                    <!-- <ApplicationLogo imgSource="/images/Chatbot.png" /> -->
                                    CIEBOT
                                </Link>
                            </div>
                            <!-- <div class="flex py-auto gap-5">
                                <ThemeSelector
                                    :currentTheme="currentTheme"
                                    @theme-changed="updateTheme"
                                />
                                <NavLink
                                    :href="route('contact')"
                                    :active="route().current('contact')"
                                >
                                    Contact
                                </NavLink>
            
                                <NavLink
                                    :href="route('about')"
                                    :active="route().current('about')"
                                >
                                    About
                                </NavLink>
                            </div> -->

                            <div class="flex py-auto gap-5">
                                <div
                                    class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                                >
                                    <!-- <NavLink
                                    :href="route('privacy')"
                                    :active="route().current('privacy')"
                                >
                                    Privacy
                                </NavLink> -->
                                    <!-- <ThemeSelector /> -->

                                    <div
                                        class="my-auto gap-2 text-sm md:ml-0 ml-3 mb-2"
                                    >
                                        <select
                                            v-model="selectedTheme"
                                            @change="changeTheme"
                                            class="rounded-xl text-sm"
                                        >
                                            <option value="theme1">
                                                Default
                                            </option>
                                            <option value="theme2">
                                                Vintage
                                            </option>
                                            <option value="theme3">
                                                Night
                                            </option>
                                            <option value="theme4">
                                                Retro
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div
                                    class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                                >
                                    <NavLink
                                        :href="route('contact')"
                                        :active="route().current('contact')"
                                    >
                                        Contact
                                    </NavLink>
                                </div>
                                <div
                                    class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                                >
                                    <NavLink
                                        :href="route('about')"
                                        :active="route().current('about')"
                                    >
                                        About
                                    </NavLink>
                                </div>
                            </div>

                            <div class="-me-2 flex items-center sm:hidden">
                                <button
                                    class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                    @click="
                                        showingNavigationDropdown =
                                            !showingNavigationDropdown
                                    "
                                >
                                    <svg
                                        class="h-6 w-6"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            :class="{
                                                hidden: showingNavigationDropdown,
                                                'inline-flex':
                                                    !showingNavigationDropdown,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            :class="{
                                                hidden: !showingNavigationDropdown,
                                                'inline-flex':
                                                    showingNavigationDropdown,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div
                        :class="{
                            block: showingNavigationDropdown,
                            hidden: !showingNavigationDropdown,
                        }"
                        class="sm:hidden"
                    >
                        <div class="pt-2 my-auto">
                            <div
                                class="my-auto gap-2 text-sm md:ml-0 ml-3 mb-2"
                            >
                                <select
                                    v-model="selectedTheme"
                                    @change="changeTheme"
                                    class="rounded-xl text-sm"
                                >
                                    <option value="theme1">Default</option>
                                    <option value="theme2">Vintage</option>
                                    <option value="theme3">Night</option>
                                    <option value="theme4">Retro</option>
                                </select>
                            </div>
                            <ResponsiveNavLink
                                :href="route('contact')"
                                :active="route().current('contact')"
                            >
                                CONTACT
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('about')"
                                :active="route().current('about')"
                            >
                                ABOUT
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </nav>
                <main>
                    <slot :class="selectedTheme" />
                </main>
            </div>
            <Footer />
        </div>
    </template>
</template>

<script>
export default {
    data() {
        return {
            selectedTheme: this.getStoredTheme(),
        };
    },
    methods: {
        changeTheme() {
            this.setStoredTheme(this.selectedTheme);
            this.$emit("theme-changed", this.selectedTheme);
        },

        getStoredTheme() {
            return localStorage.getItem("currentTheme") || "theme1";
        },
        setStoredTheme(theme) {
            localStorage.setItem("currentTheme", theme);
        },
        initializeTheme() {
            const storedTheme = this.getStoredTheme();
            this.selectedTheme = storedTheme || "theme1"; // Set a default theme if none is stored
            this.setStoredTheme(this.selectedTheme); // Update the stored theme
        },

        switchTheme(selectedTheme) {
            this.updateTheme(selectedTheme);
        },
        isTheme2() {
            return this.selectedTheme === "theme2";
        },
        isTheme3() {
            return this.selectedTheme === "theme3";
        },
        isTheme4() {
            return this.selectedTheme === "theme4";
        },
        getBackgroundImage1() {
            if (this.isTheme2()) {
                return "url(images/12.png)";
            } else if (this.isTheme3()) {
                return "url(images/14.png)";
            } else if (this.isTheme4()) {
                return "url(images/16.png)";
            } else {
                return "url(images/Page1Fin.png)";
            }
        },

        getBackgroundImage2() {
            if (this.isTheme2()) {
                return "url(images/11.png)";
            } else if (this.isTheme3()) {
                return "url(images/13.png)";
            } else if (this.isTheme4()) {
                return "url(images/15.png)";
            } else {
                return "url(images/Page2Fin.png)";
            }
        },
    },
    mounted() {
        this.selectedTheme = this.getStoredTheme();
    },
    created() {
        this.initializeTheme();
    },
};
</script>

<style>
.theme-transition {
    transition: background-image 0.5s ease-in-out; /* Adjust the duration and timing function as needed */
}
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
