<div @if ($modalId) id="{{ $modalId }}" @endif class="inset-0 fixed z-50 hidden p-2">
    <div class="flex items-center justify-center min-h-screen">
        <div class="fixed inset-0 transition-all duration-300 backdrop-blur-sm" aria-hidden="true">
            <div class="z-[100] inset-0 bg-black opacity-50 absolute"></div>
        </div>
        <div
            class="bg-white rounded-lg shadow-xl transform transition-all w-full max-w-screen-sm dark:bg-[#161616] dark:text-white animate-slideTop">
            <div class="bg-red-900 rounded-t-lg relative dark:bg-[#CCAA2C] transition-all duration-300">
                <div class="flex justify-end items-center p-4 border-b border-transparent z-10 relative">
                    <i class="fa-solid fa-circle-xmark md:text-4xl text-2xl  text-white cursor-pointer"
                        @if ($closeBtnId) id="{{ $closeBtnId }}" @endif></i>
                </div>
                <div class="w-full h-full flex justify-center items-center absolute inset-0 z-0">
                    <span class="text-white font-bold md:text-xl text-lg">
                        @if ($title)
                            {{ $title }}
                        @endif
                    </span>
                </div>
            </div>
            <main class="px-3">
                {{ $slot }}
            </main>
        </div>
    </div>
</div>
