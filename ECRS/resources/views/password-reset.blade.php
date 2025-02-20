<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>Set New Password</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/web-config.js')
    @vite('resources/fontawesome/css/all.min.css')
    @vite('resources/js/reset-pass.js')
    <script>
        (function() {
            const darkMode = localStorage.getItem('darkMode');
            if (darkMode === 'true') {
                document.documentElement.classList.add('dark');
            } else if (!darkMode && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

<body>
    {{-- <div
        class="flex flex-col min-h-screen scrollbar-thin  scrollbar-thumb-[#CCAA2C] scrollbar-track-gray-300 scroll-smooth">
        <x-set-pass-nav-bar /> --}}
    {{-- <main class="flex-1 px-3 w-full dark:bg-[#1E1E1E] transition-all duration-300 flex flex-col justify-center items-center">
            <div class="dark:text-white w-full px-96">
                <div class="flex flex-col justify-center items-center">
                    <div class="flex flex-col gap-3">
                        <div
                            class="w-full flex justify-center items-center md:text-4xl text-2xl text-red-900 dark:text-[#CCAA2C] font-bold">
                            Set New Password
                        </div>

                    </div>
                    <form id="password-reset-form" method="POST">
                        @csrf
                        <input type="hidden" id="token" name="token" value="{{ $token }}">
                        <input type="hidden" id="role" name="role" value="{{ $role }}">

                        <div class="bg-white border border-gray-300 rounded-lg shadow-lg md:w-96 w-full p-6 my-5">
                            <div class="flex flex-col justify-center items-center gap-4">
                                <div class="dark:text-gray-700 w-3/4">
                                    <label for="new-password" class="block font-bold">New Password</label>
                                    <div class="pb-2 text-sm">
                                        <span>Must have at least 8 characters long</span>
                                    </div>
                                    <div class="relative">
                                        <input type="password" name="newPassword" id="new-password"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                            autocomplete="off" placeholder="New Password" required />
                                        <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-3  -translate-y-1/5 text-gray-600"
                                            toggle="#new-password"></i>
                                    </div>
                                </div>
                                <div class="dark:text-gray-700 relative w-3/4">
                                    <label for="confirm-password" class="block font-bold">Confirm Password</label>
                                    <div class="relative">
                                        <input type="password" name="confirmPassword" id="confirm-password"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                            autocomplete="off" placeholder="Re-type New Password" required />
                                        <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-3 -translate-y-1/5 text-gray-600"
                                            toggle="#confirm-password"></i>
                                    </div>
                                </div>
                            </div>
                            @if ($role == 1)
                                <div class="flex justify-center pt-5 gap-2">
                                    <button id="set-new-pass-btn"
                                        class="text-white rounded-lg py-1 px-3 shadow-lg border border-gray-300 bg-red-900 dark:bg-[#CCAA2C] dark:text-white">
                                        Save changes
                                    </button>
                                </div>
                            @elseif($role == 2)
                                <div class="flex justify-center pt-5 gap-2">
                                    <button id="set-new-pass-btn-ad"
                                        class="text-white rounded-lg py-1 px-3 shadow-lg border border-gray-300 bg-red-900 dark:bg-[#CCAA2C] dark:text-white">
                                        Save changes
                                    </button>
                                </div>
                            @else
                                <div class="flex justify-center pt-5 gap-2">
                                    <button id="set-new-pass-btn-st"
                                        class="text-white rounded-lg py-1 px-3 shadow-lg border border-gray-300 bg-red-900 dark:bg-[#CCAA2C] dark:text-white">
                                        Save changes
                                    </button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </main> --}}

    {{-- <main
            class="flex-1 px-3 w-full  dark:bg-[#1E1E1E] transition-all duration-300 flex flex-col justify-center items-center ">
            <div class="dark:text-white w-full">
                <div class="flex flex-col justify-center items-center animate-fadeIn">
                    <div class="flex flex-col gap-3">
                        <div
                            class="w-full flex justify-center items-center md:text-4xl text-2xl text-red-900 dark:text-[#CCAA2C] font-bold">
                            Set New Password
                        </div>
                    </div>
                    <form id="password-reset-form" method="POST" class="md:w-96 w-full">
                        @csrf
                        <input type="hidden" id="token" name="token" value="{{ $token }}">
                        <input type="hidden" id="role" name="role" value="{{ $role }}">

                        <div class="bg-white border border-gray-300 rounded-lg shadow-lg  p-4 my-5">
                            <div class="flex flex-col justify-center items-center gap-4">
                                <div class="dark:text-gray-700 md:w-3/4 w-full">
                                    <label for="new-password" class="block font-bold">New Password</label>
                                    <div class="pb-2 text-sm">
                                        <span>Must have at least 8 characters long</span>
                                    </div>
                                    <div class="relative">
                                        <input type="password" name="newPassword" id="new-password"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                            autocomplete="off" placeholder="New Password" required />
                                        <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-3  -translate-y-1/5 text-gray-600"
                                            toggle="#new-password"></i>
                                    </div>
                                </div>
                                <div class="dark:text-gray-700 relative md:w-3/4 w-full">
                                    <label for="confirm-password" class="block font-bold">Confirm Password</label>
                                    <div class="relative">
                                        <input type="password" name="confirmPassword" id="confirm-password"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                            autocomplete="off" placeholder="Re-type New Password" required />
                                        <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-3 -translate-y-1/5 text-gray-600"
                                            toggle="#confirm-password"></i>
                                    </div>
                                </div>
                            </div>
                            @if ($role == 1)
                                <div class="flex justify-center pt-5 gap-2">
                                    <button id="set-new-pass-btn"
                                        class="text-white rounded-lg py-1 px-3 shadow-lg border border-gray-300 bg-red-900 dark:bg-[#CCAA2C] dark:text-white">
                                        Save changes
                                    </button>
                                </div>
                            @elseif($role == 2)
                                <div class="flex justify-center pt-5 gap-2">
                                    <button id="set-new-pass-btn-ad"
                                        class="text-white rounded-lg py-1 px-3 shadow-lg border border-gray-300 bg-red-900 dark:bg-[#CCAA2C] dark:text-white">
                                        Save changes
                                    </button>
                                </div>
                            @else
                                <div class="flex justify-center pt-5 gap-2">
                                    <button id="set-new-pass-btn-st"
                                        class="text-white rounded-lg py-1 px-3 shadow-lg border border-gray-300 bg-red-900 dark:bg-[#CCAA2C] dark:text-white">
                                        Save changes
                                    </button>
                                </div>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </main> --}}
    {{-- <div>
            <x-footer />
        </div>
        <x-web-settings />
    </div> --}}

    <div
        class="flex flex-col min-h-screen scrollbar-thin  scrollbar-thumb-[#CCAA2C] scrollbar-track-gray-300 scroll-smooth">
        <x-set-pass-nav-bar />
        <main
            class="flex-1 px-3 w-full  dark:bg-[#1E1E1E] transition-all duration-300 flex flex-col justify-center items-center ">
            <div class=" w-full">
                <div class="flex flex-col justify-center items-center animate-fadeIn">
                    <div class="flex flex-col gap-3">
                        <div class="w-full flex justify-center items-center">
                            <x-titleText>
                                <p> Set New Password</p>
                            </x-titleText>
                        </div>
                    </div>
                    <form id="password-reset-form" method="POST" class="md:w-[30rem] w-full">
                        @csrf
                        <input type="hidden" id="token" name="token" value="{{ $token }}">
                        <input type="hidden" id="role" name="role" value="{{ $role }}">

                        <div
                            class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black  rounded-md animate-fadeIn shadow-lg p-4 my-5">
                            <div class="my-6">
                                <div class="flex flex-col justify-center items-center gap-4">
                                    <div class=" md:w-3/4 w-full">
                                        <label for="new-password" class="block font-bold">New Password</label>
                                        {{-- <div class="pb-2 text-sm">
                                            <span>Must have at least 8 characters long</span>
                                            
                                        </div> --}}
                                        <div id="password-requirements" class="text-xs text-left text-gray-500  my-3">
                                            <div id="length-requirement" class="mr-2">
                                                &#8226; Password must be at least 8 characters long
                                            </div>
                                            <div id="uppercase-requirement" class="mr-2">
                                                &#8226; Password must include at least one (1) uppercase letter
                                            </div>
                                            <div id="special-char-requirement" class="mr-2">
                                                &#8226; Password must include at least one (1) special character
                                            </div>
                                            <div id="number-requirement" class="mr-2">
                                                &#8226; Password must include at least one (1) number
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <input type="password" name="newPassword" id="new-password"
                                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                                autocomplete="off" placeholder="New Password" required />
                                            <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-3  -translate-y-1/5 text-gray-600"
                                                toggle="#new-password"></i>
                                        </div>
                                    </div>
                                    <div class=" relative md:w-3/4 w-full">
                                        <label for="confirm-password" class="block font-bold">Confirm Password</label>
                                        <div class="relative">
                                            <input type="password" name="confirmPassword" id="confirm-password"
                                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                                autocomplete="off" placeholder="Re-type New Password" required />
                                            <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-3 -translate-y-1/5 text-gray-600"
                                                toggle="#confirm-password"></i>
                                        </div>
                                    </div>
                                </div>
                                @if ($role == 1)
                                    <div class="flex justify-center pt-5 gap-2">
                                        <button id="set-new-pass-btn"
                                            class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                            Save changes
                                        </button>
                                    </div>
                                @elseif($role == 2)
                                    <div class="flex justify-center pt-5 gap-2">
                                        <button id="set-new-pass-btn-ad"
                                            class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                            Save changes
                                        </button>
                                    </div>
                                @else
                                    <div class="flex justify-center pt-5 gap-2">
                                        <button id="set-new-pass-btn-st"
                                            class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                            Save changes
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <div>
            <x-footer />
        </div>
        <x-web-settings />
    </div>
</body>



</html>
