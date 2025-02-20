@php
    $roleNum = session('role');
    $loginID = session('loginID');
@endphp

@include('components.session')
<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/web-config.js')
    @vite('resources/fontawesome/css/all.min.css')
    {{-- @vite('resources/css/toastr.css')
    @vite('resources/js/toastr.js') --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ECRS</title>
    <script>
        (function() {
            const darkMode = localStorage.getItem('darkMode');
            if (darkMode === 'true') {
                document.documentElement.classList.add('dark');
            } else if (!darkMode && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.remove('dark');
            }
        })();

        // const pusherKey = '{{ config('broadcasting.connections.pusher.key') }}';

        // console.log("fsadfds", pusherKey);


        // const IDLE_TIMEOUT = 300000;

        // let idleTimer;

        // function resetIdleTimer() {
        //     clearTimeout(idleTimer);
        //     idleTimer = setTimeout(redirectToLogout, IDLE_TIMEOUT);
        // }

        // function redirectToLogout() {
        //     window.location.href = '/logout';
        // }

        // document.addEventListener('mousemove', resetIdleTimer);
        // document.addEventListener('keydown', resetIdleTimer);
        // resetIdleTimer();
    </script>
</head>
{{-- <span id="role" class="hidden">{{ $roleNum }}</span> --}}

<body id="fontSize">
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
                    text: '{{ session('error') }}'
                });
            });
        </script>
    @endif

    @if ($roleNum == 1)
        <div
            class="flex flex-col min-h-screen scrollbar-thin  scrollbar-thumb-[#CCAA2C] scrollbar-track-gray-300 scroll-smooth">
            <x-top-navbar :loginID="$loginID" :userinfo="$userinfo" :user="$user" :role="$role" :unreadCountFeedback="$unreadCountFeedback" />
            <main class="flex-1 md:px-9 px-5 pb-10 w-full mt-10  md:mt-14 dark:bg-[#1E1E1E] transition-all duration-300 relative">
                @yield('content')
            </main>
            <div>
                <x-footer />
            </div>
            <x-web-settings />
        </div>
    @elseif ($roleNum == 2)
        <div class="flex min-h-screen relative">
            <div class="flex flex-1 flex-col relative">
                <x-top-navbar :loginID="$loginID" :userinfo="$userinfo" :user="$user" :role="$role" :notifications="$notifications"
                     />
                <main class="flex flex-1 w-full relative h-screen mt-14 dark:bg-[#1E1E1E]">
                    <div class="flex w-full pl-28 pr-10  pb-10 transition-all duration-300">
                        @yield('content')
                    </div>
                    <x-sidebar class="h-full absolute top-0 left-0 transition-all duration-300 z-10" />
                </main>
                <div>
                    <x-footer />
                </div>
                <x-web-settings />
            </div>
        </div>
    @elseif ($roleNum == 3)
        <div
            class="flex flex-col min-h-screen scrollbar-thin  scrollbar-thumb-[#CCAA2C] scrollbar-track-gray-300 scroll-smooth">
            <x-top-navbar :loginID="$loginID" :userinfo="$userinfo" :user="$user" :role="$role" :notifications="$notifications"
                :unreadCount="$unreadCount" />
            <main class="flex-1 md:px-9 px-5 w-full mt-10 pb-10 md:mt-14 dark:bg-[#1E1E1E] transition-all duration-300 relative">
                @yield('content')
            </main>
            <div>
                <x-footer />
            </div>
            <x-web-settings />
        </div>
    @elseif ($roleNum == 4)
        <div class="flex min-h-screen relative">
            <div class="flex flex-1 flex-col relative">
                <x-top-navbar :loginID="$loginID" :userinfo="$userinfo" :user="$user" :role="$role"
                    :notifications="$notifications" :unreadCount="$unreadCount" />
                <main class="flex flex-1 w-full relative h-screen pb-10 mt-14 dark:bg-[#1E1E1E]">
                    <div class="flex w-full pl-28  pb-10  pr-8 transition-all duration-300">
                        @yield('content')
                    </div>
                    <x-sidebar class="h-full absolute top-0 left-0 transition-all duration-300 z-10" />
                </main>
                <div>
                    <x-footer />
                </div>
                <x-web-settings />
            </div>
        </div>
    @else
        <div
            class="flex flex-col min-h-screen scrollbar-thin  scrollbar-thumb-[#CCAA2C] scrollbar-track-gray-300 scroll-smooth">
            <x-top-navbar />
            <main
                class="flex-1 px-3 w-full dark:bg-[#1E1E1E] transition-all duration-300 h-full flex flex-col justify-center items-center">
                @yield('content')
            </main>
            <div>
                <x-footer />
            </div>
            <x-web-settings />
        </div>
    @endif
</body>


</html>
