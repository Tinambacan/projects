<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password</title>
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
    <div
        class="flex flex-col min-h-screen scrollbar-thin  scrollbar-thumb-[#CCAA2C] scrollbar-track-gray-300 scroll-smooth">
        <x-set-pass-nav-bar />
        {{-- <main
            class="flex-1 px-3 w-full  dark:bg-[#1E1E1E] transition-all duration-300 flex flex-col justify-center items-center">
            <div class="dark:text-white w-full px-96">
                <div class="flex flex-col justify-center items-center animate-fadeIn">
                    <div class="flex flex-col gap-3">
                        <div
                            class="w-full flex justify-center items-center md:text-4xl text-2xl text-red-900 dark:text-[#CCAA2C] font-bold">
                            Forgot Password?
                        </div>
                        <div class="text-gray-800 dark:text-white">
                            No problem, we'll send you an email with instructions to reset it.
                        </div>
                    </div>
                    <form id="password-change-form" method="POST">
                        @csrf
                        <div class="bg-white border border-gray-300 rounded-lg shadow-lg  md:w-96 w-full p-10 my-5">
                            <div class="items-center dark:text-gray-700">
                                <label for="email" class="block font-bold">Email Address:</label>
                                <input type="text" name="email" id="email"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="Enter existing email" required />
                            </div>

                            <div class="flex justify-center pt-5 gap-2">
                                <button id="submit-btn-stud"
                                    class="text-white rounded-lg py-1 px-3 shadow-lg border border-gray-300 bg-red-900 dark:bg-[#CCAA2C] dark:text-white">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center">
                        <span class=" text-gray-800 dark:text-white">I remember my password,</span> <a
                            href="{{ route('student.login') }}" class="text-blue-600 underline">Login</a>
                    </div>
                </div>
            </div>
        </main> --}}

        <main
            class="flex-1 px-3 w-full  dark:bg-[#1E1E1E] transition-all duration-300 flex flex-col justify-center items-center ">
            <div class="dark:text-white w-full">
                <div class="flex flex-col justify-center items-center animate-fadeIn">
                    <div class="flex flex-col gap-3">
                        <div class="w-full flex justify-center items-center font-bold">
                            <x-titleText>
                                <p>Forgot Password?</p>
                            </x-titleText>
                        </div>
                        <div class="text-gray-800 dark:text-white">
                            No problem, we'll send you an email with instructions to reset it.
                        </div>
                    </div>
                    <form id="password-change-form" method="POST" class="md:w-[30rem] w-full">
                        @csrf
                        <div class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black  rounded-md animate-fadeIn shadow-lg p-5 my-5">
                            <div class="items-center dark:text-white">
                                <label for="email" class="block font-bold">Email Address:</label>
                                <input type="text" name="email" id="email"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="Enter existing email" required />
                            </div>

                            <div class="flex justify-center pt-5 gap-2">
                                {{-- <button id="submit-btn-stud"
                                    class="text-white rounded-lg py-1 px-3 shadow-lg border border-gray-300 bg-red-900 dark:bg-[#CCAA2C] dark:text-white">
                                    Submit
                                </button> --}}
                                <x-button id="submit-btn-stud">
                                    <span>Submit</span>
                                </x-button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center">
                        <span class="text-gray-800 dark:text-white">I remember my password,</span> <a
                            href="{{ route('student.login') }}" class="text-blue-600 underline">Login</a>
                    </div>
                </div>
            </div>
        </main>
        <div>
            <x-footer />
        </div>
        <x-web-settings />
    </div>
</body>
<x-loader modalLoaderId="send-email-loader-st" />

</html>
