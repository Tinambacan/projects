@vite('resources/js/app.js')
@vite('resources/js/information.js')

<div class="flex justify-start items-start">
    <div class="relative group flex justify-end items-end">
        <div id="info-button">
            <i
                class="fa-solid fa-circle-question text-2xl dark:bg-white dark:text-[#CCAA2C] text-red-900  rounded-full  cursor-pointer "></i>
        </div>
        <div class="absolute top-full transform hidden group-hover:block -translate-x-1/2">
            <div class="flex justify-end items-end transition-all duration-300 relative">
                <span id="info-content" class="shadow-lg z-50 absolute rounded-lg mb-10 md:w-96 w-72 animate-fadeIn">
                    <div
                        class="border border-zinc-300 dark:border-[#404040] rounded-lg overflow-hidden bg-white dark:bg-[#1E1E1E]">
                        <main>
                            {{ $slot }}
                        </main>
                    </div>
                </span>
            </div>
        </div>
    </div>
</div>
