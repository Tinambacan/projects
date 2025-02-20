<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>Page Expired</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/web-config.js')
    @vite('resources/fontawesome/css/all.min.css')
</head>


<body>
    <div
        class="flex flex-col min-h-screen scrollbar-thin  scrollbar-thumb-[#CCAA2C] scrollbar-track-gray-300 scroll-smooth">
        <x-set-pass-nav-bar />
        <main
            class="flex-1 px-3 w-full  dark:bg-[#1E1E1E] transition-all duration-300 flex flex-col justify-center items-center">
            <div class="dark:text-white w-full  md:px-96">
                <div class="flex flex-col justify-center items-center">
                    <div class="flex flex-col gap-3">
                        <div
                            class="w-full flex justify-center items-center md:text-4xl text-2xl  text-red-900 dark:text-[#CCAA2C] font-bold">
                            This page is expired
                        </div>
                        <div class="text-gray-800 dark:text-white md:text-xl text-md text-center">
                            The password reset link has expired or is invalid. Please request a new link.
                        </div>
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


</html>
