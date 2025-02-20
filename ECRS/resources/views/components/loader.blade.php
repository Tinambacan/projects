<div @if ($modalLoaderId) id="{{ $modalLoaderId }}" @endif class="inset-0 fixed z-50 hidden">
    <div class="flex items-center justify-center min-h-screen px-2">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="z-[100] inset-0 bg-black opacity-50 absolute"></div>
        </div>
        <div
            class="bg-white rounded-lg shadow-xl transform transition-all w-full max-w-screen-sm flex items-center justify-center p-10 dark:bg-[#161616]">
            <div class="flex flex-col items-center gap-4">
                <i class="fa-solid fa-spinner fa-spin text-4xl text-red-900 dark:text-[#CCAA2C]"></i>
                {{-- <span class="text-xl font-bold dark:text-white text-gray-700" id="loader-title">{{ $titleLoader }}</span> --}}
                <span class="text-lg font-bold dark:text-white text-gray-700" id="loader-title">
                    {{ $titleLoader ?? '' }}
                </span>
            </div>
        </div>
    </div>
</div>
