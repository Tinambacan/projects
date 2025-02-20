<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>Page Not Found</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/web-config.js')
    {{-- @vite('resources/js/error-page.js') --}}
    @vite('resources/fontawesome/css/all.min.css')
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
        <div
            class="flex-1 px-3 w-full  dark:bg-[#1E1E1E] transition-all duration-300 flex flex-col justify-center items-center">
            <div class=" w-full">
                <div class="flex flex-col justify-center items-center animate-fadeIn">
                    <div class="md:w-[30rem] w-full">
                        <div
                            class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black  rounded-md animate-fadeIn shadow-lg p-4 my-5 flex flex-col justify-center items-center">
                            <div class="flex flex-col gap-3">
                                <div
                                    class="w-full flex justify-center items-center md:text-4xl text-2xl text-red-900 dark:text-[#CCAA2C] font-bold flex-col">
                                    <span>404</span> Page Not Found
                                </div>
                            </div>
                            <div class="my-6 font-bold">
                                Sorry, the page you are looking for could not be found.
                            </div>
                            <x-button onclick="history.back()">
                                Back to page
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <x-footer />
        </div>
        <x-web-settings />
    </div>

</body>

</html>
