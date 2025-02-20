<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/login.js')
    @vite('resources/fontawesome/css/all.min.css')
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>Faculty Login</title>
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

@if (session('status') === 'success')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('message') }}',
                showConfirmButton: false,
                timer: 2000
            });
        });
    </script>
@endif

@if (session('status') === 'warning')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: '{{ session('warning') }}'
            });
        });
    </script>
@endif

@if (session('status') === 'error')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please check your credentials'
            });
        });
    </script>
@endif



<body class="bg-cover bg-center bg-no-repeat min-h-screen flex justify-center items-center"
    style="background-image: url('images/pup-bg.jpg');">
    <div class="flex w-full justify-center lg:justify-end relative min-h-screen p-5">
        <div
            class="bg-white dark:bg-[#161616] max-w-md sm:max-w-lg lg:max-w-lg w-full rounded-lg px-4 sm:px-6 md:px-12 flex flex-col justify-center relative animate-fadeIn">
            <a class="flex justify-start gap-2 absolute top-4 md:top-10 hover:bg-gray-100 dark:hover:bg-[#1E1E1E] rounded-full p-2"
                href="/">
                <i
                    class="fa-solid fa-circle-arrow-left text-2xl rounded-full text-red-900 dark:bg-white dark:text-[#CCAA2C]"></i>
                <span class="flex justify-center items-center text-sm md:text-base dark:text-white">Back</span>
            </a>
            <div class="flex justify-center text-center flex-col mb-6 md:mb-0 max-xl:mt-10 mt-12">
                <div class="flex justify-center items-center flex-col">
                    {{-- <span
                        class="text-3xl sm:text-4xl md:text-4xl lg:text-5xl font-bold text-red-900 dark:text-white">LOGIN</span> --}}
                    <x-titleText>
                        LOGIN
                    </x-titleText>
                    <img src="{{ URL('images/logo-bg.png') }}" alt="fds"
                        class="h-24 sm:h-28 md:h-32 lg:h-[20vh] mt-1">
                </div>
                <div>
                    <div class="text-2xl sm:text-2xl md:text-3xl lg:text-4xl font-medium dark:text-white">
                        Good day, <span class="font-bold text-red-900 dark:text-[#CCAA2C]">Faculty!</span>
                    </div>
                    <div>
                        <form class="flex gap-3 sm:gap-4 md:gap-5 pt-6 sm:pt-6 md:pt-6 flex-col" method="POST">
                            @csrf
                            {{-- <div class="relative mt-2 flex">
                                <input autocomplete="off" type="text" name="email_or_schoolIDNo"
                                    class="bg-[#E5E3DA] block rounded-lg pl-10 sm:pl-12 md:pl-12 pt-2 w-full max-w-full text-gray-900 peer h-14 dark:border-white dark:text-white border dark:bg-[#8F8F8F]"
                                    placeholder=" " required>
                                <i class="fa-solid fa-user text-lg sm:text-2xl md:text-2xl text-[#404040] dark:text-white left-3"
                                    style="position: absolute; top: 50%; transform: translateY(-50%);"></i>
                                <label for="email_or_schoolIDNo"
                                    class="absolute text-xs sm:text-sm md:text-md text-[#8F8F8F] dark:text-white duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-10 sm:left-12 md:left-12 peer-focus:text-blue-600 peer-focus:font-bold peer-focus:dark:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4">
                                    Email/Faculty Number</label>
                            </div> --}}

                            <div class="relative mt-2 flex">
                                <input autocomplete="off" type="text" name="email_or_schoolIDNo"
                                    id="email_or_schoolIDNo"
                                    class="bg-[#E5E3DA] block rounded-lg pl-10 sm:pl-12 md:pl-12 pt-2 w-full max-w-full text-gray-900 peer h-14 dark:border-white dark:text-white border dark:bg-[#8F8F8F]"
                                    placeholder=" " required>
                                <i class="fa-solid fa-user text-lg sm:text-2xl md:text-2xl text-[#404040] dark:text-white left-3"
                                    style="position: absolute; top: 50%; transform: translateY(-50%);"></i>
                                <label for="email_or_schoolIDNo"
                                    class="absolute text-xs sm:text-sm md:text-md text-[#8F8F8F] dark:text-white duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-10 sm:left-12 md:left-12 
                                    peer-placeholder-shown:translate-y-0 
                                    peer-placeholder-shown:scale-100 
                                    peer-focus:translate-y-[-1rem] 
                                    peer-focus:scale-75 
                                    peer-focus:text-blue-600 
                                    peer-focus:font-bold 
                                    peer-focus:dark:text-black">
                                    Email/Faculty Number
                                </label>
                            </div>


                            {{-- <div class="relative flex">
                                <input type="password" name="password" id="password"
                                    class="bg-[#E5E3DA] block rounded-lg pl-10 sm:pl-12 md:pl-12 pt-2  pr-10 sm:pr-12 md:pr-14 w-full max-w-full  text-gray-900 peer h-14 dark:border-white dark:text-white border dark:bg-[#8F8F8F]"
                                    placeholder=" " required>
                                <i class="fa-solid fa-lock text-lg sm:text-2xl md:text-2xl text-[#404040] dark:text-white left-3"
                                    style="position: absolute;  top: 50%; transform: translateY(-50%);"></i>
                                <label for="password"
                                    class="absolute text-xs sm:text-sm md:text-md text-[#8F8F8F] dark:text-white duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-10 sm:left-12 md:left-12 peer-focus:text-blue-600 peer-focus:font-bold peer-focus:dark:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4">
                                    Password</label>
                                <div class="flex justify-center items-center">
                                    <i class="fas fa-eye text-lg sm:text-2xl md:text-2xl text-[#404040] dark:text-white toggle-password cursor-pointer absolute right-[10px]"
                                        toggle="#password"></i>
                                </div>
                            </div> --}}

                            <div class="relative flex">
                                <!-- Input Field -->
                                <input type="password" name="password" id="password"
                                    class="bg-[#E5E3DA] block rounded-lg pl-10 sm:pl-12 md:pl-12 pt-2 pr-10 sm:pr-12 md:pr-14 w-full max-w-full text-gray-900 peer h-14 dark:border-white dark:text-white border dark:bg-[#8F8F8F]"
                                    placeholder=" " required>

                                <!-- Lock Icon -->
                                <i class="fa-solid fa-lock text-lg sm:text-2xl md:text-2xl text-[#404040] dark:text-white left-3"
                                    style="position: absolute; top: 50%; transform: translateY(-50%);"></i>

                                <!-- Label -->
                                <label for="password"
                                    class="absolute text-xs sm:text-sm md:text-md text-[#8F8F8F] dark:text-white duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-10 sm:left-12 md:left-12 
                                    peer-placeholder-shown:translate-y-0 
                                    peer-placeholder-shown:scale-100 
                                    peer-focus:translate-y-[-1rem] 
                                    peer-focus:scale-75 
                                    peer-focus:text-blue-600 
                                    peer-focus:font-bold 
                                    peer-focus:dark:text-black">
                                    Password
                                </label>

                                <!-- Eye Icon for Toggling Password Visibility -->
                                <div class="flex justify-center items-center">
                                    <i class="fas fa-eye text-lg sm:text-2xl md:text-2xl text-[#404040] dark:text-white toggle-password cursor-pointer absolute right-[10px]"
                                        toggle="#password"></i>
                                </div>
                            </div>



                            <div class="justify-between flex flex-wrap">
                                <a href="{{ route('faculty.send-email-pass') }}"
                                    class="text-[#8F8F8F] mt-2 hover:underline cursor-pointer text-xs sm:text-sm md:text-lg dark:text-white">
                                    <strong>Forgot your password?</strong>
                                </a>

                                <button type="submit" id="myLoginFaculty"
                                    class="p-2 rounded-lg hover:bg-gray-200 text-[#CCAA2C] dark:text-black dark:bg-white border border-[#B7B4B4] dark:border-gray-600 w-20 sm:w-32 cursor-pointer text-xs sm:text-sm md:text-lg font-bold">
                                    Login
                                </button>
                            </div>
                            <div class="text-xs sm:text-sm mt-4 md:mt-0 dark:text-gray-400 text-center pb-8">
                                Terms of Use | Privacy Policy
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-web-settings />
</body>

</html>
