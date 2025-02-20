<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    @vite('resources/fontawesome/css/all.min.css')
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>User Type</title>
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

<body class="bg-cover bg-center bg-no-repeat min-h-screen flex justify-center items-center"
    style="background-image: url('images/pup-bg.jpg');">
    <div class="flex w-full justify-center lg:justify-end relative min-h-screen p-5">
        <div
            class="bg-white dark:bg-[#161616] max-w-md sm:max-w-lg lg:max-w-lg w-full rounded-lg px-4 sm:px-6 md:px-12 flex flex-col justify-center relative animate-fadeIn">
            <div class="flex flex-col justify-center items-center mb-16 md:mb-24">
                <div class="mb-4">
                    <img src="{{ URL('images/logo-bg.png') }}" alt="logo" class="h-28 md:h-[20vh]">
                </div>
                <div class="flex gap-2 flex-col text-center">
                    <div class="text-2xl sm:text-xl md:text-2xl xl:text-3xl font-medium dark:text-white">Good day,
                        <span class="font-bold text-[#CCAA2C]">PUPian!</span>
                    </div>
                    <span class="italic dark:text-white">Please select your user type</span>
                </div>

                <div class="flex  md:flex-row gap-6 md:gap-10 pt-8 md:pt-12">
                    <a class="text-center flex flex-col transition-transform transform hover:scale-105 duration-300 ease-in-out group cursor-pointer" href="faculty">
                        <div class="p-4 md:p-5 bg-red-900 text-white rounded-lg group-hover:bg-red-800">
                            <img src="{{ URL('images/faculty-icon.png') }}" alt="faculty icon"
                                class="h-12 md:h-[11vh] group-hover:opacity-75">
                        </div>
                        <div class="font-medium text-xl md:text-2xl p-2 group-hover:text-gray-700 dark:text-white">
                            Faculty</div>
                    </a>

                    <a class="text-center flex flex-col transition-transform transform hover:scale-105 duration-300 ease-in-out group cursor-pointer justify-center items-center"
                        href="student">
                        <div class="p-4 md:p-5 bg-red-900 text-white rounded-lg group-hover:bg-red-800">
                            <img src="{{ URL('images/stud-icon.png') }}" alt="student icon"
                                class="h-12 md:h-[11vh] group-hover:opacity-75 flex ">
                        </div>
                        <div class="font-medium text-xl md:text-2xl p-2 group-hover:text-gray-700 dark:text-white">
                            Student</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <x-web-settings />
</body>

</html>
