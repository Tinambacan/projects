<button 
    @isset($type) type="{{ $type }}" @endisset 
    @isset($id) id="{{ $id }}" @endisset
    {{ $attributes->merge([
        'class' =>
            'text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]',
    ]) }}
>
    {{ $slot }}
</button>

{{-- <button @isset($type) type="{{ $type }}" @endisset
    @isset($id) id="{{ $id }}" @endisset
    {{ $attributes->merge([
        'class' =>
            'text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]',
    ]) }}
    onclick="disableWithLoader(this)">
    {{ $slot }}
</button>

<script>
    function disableWithLoader(button) {
        const $button = $(button);
        $button.prop("disabled", true).html(`
        <div class="flex items-center justify-center gap-2">
            <i class="fa-solid fa-spinner fa-spin text-xl text-red-900 dark:text-[#CCAA2C]"></i>
        </div>
    `).removeClass("hover:bg-gray-200 cursor-pointer");
    }
</script> --}}
