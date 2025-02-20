<script setup>
import ApplicationLogo from "./ApplicationLogo.vue";

// import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
</script>
<template>
    <div
        class="p-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-5 w-full"
    >
        <div class="pb-2">
            <div
                class="bg-zinc-200 rounded-r-xl rounded-l-xl shadow-md flex flex-row gap-3"
            >
                <div
                    class="text-base font-bold bg-red-900 text-white text-center rounded-l-xl p-3 uppercase tracking-wider"
                >
                    <ApplicationLogo
                        imgSource="/images/Ic1.png"
                        imgStyle="height: 4rem; width: 4rem"
                    />
                </div>
                <div class="my-auto">THE HARDWARE TOOLS</div>
            </div>
        </div>
        <div class="pb-2">
            <div
                class="bg-zinc-200 rounded-r-xl rounded-l-xl shadow-md flex flex-row gap-3"
            >
                <div
                    class="text-base font-bold bg-red-900 text-white text-center rounded-l-xl p-3 uppercase tracking-wider"
                >
                    <ApplicationLogo
                        imgSource="/images/Ic2.png"
                        imgStyle="height: 4rem; md:width: 4rem;"
                    />
                </div>
                <div class="my-auto">THE COMPUTER HARDWARE</div>
            </div>
        </div>
        <div class="pb-2">
            <div
                class="bg-zinc-200 rounded-r-xl rounded-l-xl shadow-md flex flex-row gap-3"
            >
                <div
                    class="text-base font-bold bg-red-900 text-white text-center rounded-l-xl p-3 uppercase tracking-wider"
                >
                    <ApplicationLogo
                        imgSource="/images/Ic3.png"
                        imgStyle="height: 4rem; md:width: 4rem;"
                    />
                </div>
                <div class="my-auto">UTILITY AND CUSTOM SOFTWARE</div>
            </div>
        </div>
        <div class="pb-2">
            <div
                class="bg-zinc-200 rounded-r-xl rounded-l-xl shadow-md flex flex-row gap-3"
            >
                <div
                    class="text-base font-bold bg-red-900 text-white text-center rounded-l-xl p-3 uppercase tracking-wider"
                >
                    <ApplicationLogo
                        imgSource="/images/Ic4.png"
                        imgStyle="height: 4rem; width: 4rem"
                    />
                </div>
                <div class="my-auto">ADOBE APPLICATION</div>
            </div>
        </div>
        <div class="pb-2">
            <div
                class="bg-zinc-200 rounded-r-xl rounded-l-xl shadow-md flex flex-row gap-3"
            >
                <div
                    class="text-base font-bold bg-red-900 text-white text-center rounded-l-xl p-3 uppercase tracking-wider"
                >
                    <ApplicationLogo
                        imgSource="/images/Ic6.png"
                        imgStyle="height: 4rem; width: 4rem"
                    />
                </div>
                <div class="my-auto">MICROSOFT OFFICE</div>
            </div>
        </div>
    </div>
</template>
