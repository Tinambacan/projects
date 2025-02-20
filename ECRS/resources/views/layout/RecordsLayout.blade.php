@php
    $roleNum = session('role');
@endphp

<!DOCTYPE html>
@extends('layout.AppLayout')
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lists</title>
    @vite('resources/js/app.js')
    @vite('resources/js/programlist.js')
    @vite('resources/css/dataTable.css')
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
</head>

<body>
    @section('content')
        @if ($roleNum == 1)
            <div class="flex w-full transition-all duration-300">
                <div class="flex flex-col p-4 w-full">
                    <div class="flex">
                        <div class="flex gap-5">
                            <div class="font-bold text-xl mt-4 cursor-pointer p-2 rounded-t-lg border-red-900 border-2 {{ request()->routeIs('faculty.course-list') ? 'bg-red-900 text-white' : 'hover:text-gray-400' }}"
                                onclick="navigateToCourses()">
                                Courses
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="font-bold text-xl mt-4 cursor-pointer p-2 rounded-t-lg border-red-900 border-2 {{ request()->routeIs('faculty.program-list') ? 'bg-red-900 text-white' : 'hover:text-gray-400' }}"
                                onclick="navigateToPrograms()">
                                Programs
                            </div>
                        </div>
                    </div>
                    <main>
                        @yield('recordscontent')
                    </main>
                </div>
            </div>
        @elseif ($roleNum == 2)
            <div class="flex w-full transition-all duration-300 pt-3">
                <div class="flex flex-col pt-5 w-full">
                    <div class="flex">
                        <div class="flex gap-5">
                            <div class="  dark:text-white dark:border-[#B7B4B4] shadow-md w-full px-4 py-2  text-gray-700  border border-gray-300  focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2  text-xl mt-4 cursor-pointer p-2  rounded-tl-lg   {{ request()->routeIs('admin.course-list') ? 'bg-red-900 border-red-900  text-white dark:bg-[#CCAA2C] dark:border-[#CCAA2C] font-bold' : 'hover:text-gray-400 dark:bg-[#404040] dark:border-white' }}"
                                onclick="navigateToAdminCourses()">
                                Courses
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class=" dark:text-white dark:border-[#B7B4B4] shadow-md w-full px-4 py-2  text-gray-700 border border-gray-300   focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2  text-xl mt-4 cursor-pointer p-2  rounded-tr-lg   {{ request()->routeIs('admin.program-list') ? 'bg-red-900 border-red-900  text-white dark:bg-[#CCAA2C] dark:border-[#CCAA2C] font-bold' : 'hover:text-gray-400 dark:bg-[#404040] dark:border-white' }}"
                                onclick="navigateToAdminPrograms()">
                                Programs
                            </div>
                        </div>
                    </div>
                    <main class="animate-fadeIn">
                        @yield('recordscontent')
                    </main>
                </div>
            </div>
        @elseif ($roleNum == 4)
            <div class="flex w-full transition-all duration-300 ">
                <div class="flex flex-col p-4 w-full">
                    <div class="flex">
                        <div class="flex gap-5">
                            <div class="text-xl mt-4 cursor-pointer p-2  rounded-tl-lg border-gray-300 shadow-md border-2  {{ request()->routeIs('super.course-list') ? 'bg-red-900 border-red-900  text-white dark:bg-[#CCAA2C] dark:border-[#CCAA2C] font-bold' : 'hover:text-gray-400 dark:bg-white dark:border-white' }}"
                                onclick="navigateToSuperAdminCourses()">
                                Courses
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="text-xl mt-4 cursor-pointer p-2  rounded-tr-lg  border-gray-300 shadow-md border-2  {{ request()->routeIs('super.program-list') ? 'bg-red-900 border-red-900  text-white dark:bg-[#CCAA2C] dark:border-[#CCAA2C] font-bold' : 'hover:text-gray-400 dark:bg-white dark:border-white' }}"
                                onclick="navigateToSupoerAdminPrograms()">
                                Programs
                            </div>
                        </div>
                    </div>
                    <main class="animate-fadeIn">
                        @yield('recordscontent')
                    </main>
                </div>
            </div>
        @endif

        <script>
            function navigateToCourses() {
                window.location.href = "{{ route('faculty.course-list') }}";
            }

            function navigateToPrograms() {
                window.location.href = "{{ route('faculty.program-list') }}";
            }

            function navigateToAdminCourses() {
                window.location.href = "{{ route('admin.course-list') }}";
            }

            function navigateToAdminPrograms() {
                window.location.href = "{{ route('admin.program-list') }}";
            }

            
            function navigateToSuperAdminCourses() {
                window.location.href = "{{ route('super.course-list') }}";
            }

            function navigateToSupoerAdminPrograms() {
                window.location.href = "{{ route('super.program-list') }}";
            }
        </script>
    @endsection
</body>

</html>