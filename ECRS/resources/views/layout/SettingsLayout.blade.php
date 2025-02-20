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
    {{-- <title>Account Settings</title> --}}
    {{-- @vite('resources/js/app.js') --}}
    @vite('resources/js/programlist.js')
    @vite('resources/css/dataTable.css')
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
</head>

<body>
    @section('content')
        @if ($roleNum == 1)
            <div class="flex flex-col justify-center w-full pt-8">
                <div class="flex my-3 rounded-md justify-start items-start">
                    <a href="{{ route('faculty.class-record') }}"
                        class="flex gap-2 text-white p-2 dark:hover:bg-[#161616] hover:bg-gray-200 rounded-md cursor-pointer">
                        <div class="text-red-900 dark:text-[#CCAA2C] flex gap-1 justify-center items-center">
                            <i class="fa-solid fa-circle-arrow-left text-2xl"></i>
                            {{-- <i class="fa-solid fa-rectangle-list text-2xl"></i> --}}
                        </div>
                        <span class="md:text-lg text-md text-black dark:text-white">Back to class record list</span>
                    </a>
                </div>
                <div class="dark:text-white w-full pb-5 md:px-2 lg:px-12 xl:px-32 2xl:px-56">
                    <div class="flex flex-col gap-3">
                        <div class="w-full flex justify-center items-center">
                            <x-titleText>
                                Account Settings
                            </x-titleText>
                        </div>
                        <div class="flex md:flex-row flex-col gap-10">
                            <div class="flex md:flex-row flex-col gap-2  md:w-96 lg:w-72 w-full">
                                <div class="md:hidden flex justify-end">
                                    <button onclick="toggleSelectionMenu()"
                                        class="bg-red-900 dark:bg-[#CCAA2C] text-white  rounded-md p-2 flex gap-2">
                                        @if (request()->routeIs('faculty.acc-info'))
                                            <div class="flex gap-2">
                                                <div class="flex justify-center items-center">
                                                    <i
                                                        class="fa-solid fa-user text-lg  rounded-md group-hover:text-white"></i>
                                                </div>
                                                <div
                                                    class="flex justify-center items-center group-hover:text-white text-md">
                                                    <span>Personal Information</span>
                                                </div>
                                            </div>
                                        @elseif (request()->routeIs('faculty.update-pass'))
                                            <div class="flex gap-2">
                                                <div class="flex justify-center items-center">
                                                    <i
                                                        class="fa-solid fa-lock text-lg  rounded-md group-hover:text-white"></i>
                                                </div>
                                                <div
                                                    class="flex justify-center items-center group-hover:text-white text-md">
                                                    <span>Password</span>
                                                </div>
                                            </div>
                                        @elseif (request()->routeIs('faculty.archived-records'))
                                            <div class="flex gap-2">
                                                <div class="flex justify-center items-center">
                                                    <i
                                                        class="fa-solid fa-box-archive text-lg  rounded-md group-hover:text-white"></i>
                                                </div>
                                                <div
                                                    class="flex justify-center items-center group-hover:text-white text-md">
                                                    <span>Archived Class Record</span>
                                                </div>
                                            </div>
                                        @elseif(request()->routeIs('faculty.act-logs'))
                                            <div class="flex gap-2">
                                                <div class="flex justify-center items-center">
                                                    <i
                                                        class="fa-solid fa-list text-lg  rounded-md group-hover:text-white"></i>
                                                </div>
                                                <div
                                                    class="flex justify-center items-center group-hover:text-white text-md">
                                                    <span>Activity Log</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div id="caretIcon">
                                            <i class="fa-solid fa-caret-down text-lg"></i>
                                        </div>
                                    </button>
                                </div>
                                <div id="selectionMenu" class="hidden md:block">
                                    <div
                                        class=" flex flex-col gap-5 md:w-96 lg:w-72 w-full bg-white md:dark:bg-transparent dark:bg-[#161616] rounded-md p-2 shadow-lg md:shadow-none">
                                        <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C]  transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('faculty.acc-info') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                            onclick="navigateToAccountPage()">
                                            <div class="flex justify-center items-center">
                                                <i
                                                    class="fa-solid fa-user text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                            </div>
                                            <div class="flex justify-center items-center group-hover:text-white">
                                                <span>Personal Information</span>
                                            </div>
                                        </div>

                                        <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('faculty.update-pass') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                            onclick="navigateToPasswordPage()">
                                            <div class="flex justify-center items-center">
                                                <i
                                                    class="fa-solid fa-lock text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                            </div>
                                            <div class="flex justify-center items-center group-hover:text-white">
                                                <span>Password</span>
                                            </div>
                                        </div>

                                        <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('faculty.archived-records') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                            onclick="navigateToFacultyArchived()">
                                            <div class="flex justify-center items-center">
                                                <i
                                                    class="fa-solid fa-box-archive text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                            </div>
                                            <div class="flex justify-center items-center group-hover:text-white">
                                                <span>Archived Class Record</span>
                                            </div>
                                        </div>

                                        <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('faculty.act-logs') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                            onclick="navigateToActLogs()">
                                            <div class="flex justify-center items-center">
                                                <i
                                                    class="fa-solid fa-list text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                            </div>
                                            <div class="flex justify-center items-center group-hover:text-white">
                                                <span>Activity Log</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] rounded-lg shadow-lg w-full">
                                <div class="px-5 py-6">
                                    <main class="animate-fadeIn">
                                        @yield('settingscontent')
                                    </main>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($roleNum == 2)
            <div class="flex flex-col  w-full pt-14 pb-5 px-0 sm:px-0 md:px-2 lg:px-12 xl:px-32 2xl:px-56">
                <div class="dark:text-white w-full ">
                    <div class="flex flex-col gap-3">
                        <div class="w-full flex justify-center items-center">
                            <x-titleText>
                                Account Settings
                            </x-titleText>
                        </div>
                        <div class="flex gap-10">
                            <div class="flex flex-col gap-5 md:w-96 lg:w-72 w-full">
                                <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C]  transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('admin.acc-info') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                    onclick="navigateToAccountPageAdmin()">
                                    <div class="flex justify-center items-center">
                                        <i class="fa-solid fa-user text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                    </div>
                                    <div class="flex justify-center items-center group-hover:text-white">
                                        <span>Personal Information</span>
                                    </div>
                                </div>
                                <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C]  transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('admin.class-record-yearSem') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                    onclick="navigateToClassRecordSetup()">
                                    <div class="flex justify-center items-center">
                                        <i class="fa-solid fa-calendar text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                    </div>
                                    <div class="flex justify-center items-center group-hover:text-white">
                                        <span>School Year & Semester</span>
                                    </div>
                                </div>

                                <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C]  transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('admin.update-pass') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                    onclick="navigateToPasswordPageAdmin()">
                                    <div class="flex justify-center items-center">
                                        <i class="fa-solid fa-lock text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                    </div>
                                    <div class="flex justify-center items-center group-hover:text-white">
                                        <span>Password</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] rounded-lg shadow-lg w-full">
                                <div class="px-5 py-6 w-full">
                                    <main class="animate-fadeIn w-full">
                                        @yield('settingscontent')
                                    </main>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($roleNum == 4)
            <div class="flex flex-col pt-14 w-full">
                <div class="dark:text-white w-full pb-5 md:px-2 lg:px-12 xl:px-32 2xl:px-56">
                    <div class="flex flex-col gap-3 ">
                        <div class="w-full flex justify-center items-center">
                            <x-titleText>
                                Account Settings
                            </x-titleText>
                        </div>
                        <div class="flex md:flex-row flex-col gap-10">
                            <div class="flex  flex-col gap-2  md:w-96 lg:w-72 w-full">
                                <div
                                    class=" flex flex-col gap-5 md:w-96 lg:w-72 w-full bg-white md:dark:bg-transparent dark:bg-[#161616] rounded-md p-2 shadow-lg md:shadow-none">
                                    <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C]  transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('super.acc-info') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                        onclick="navigateToAccountPageSuperAdmin()">
                                        <div class="flex justify-center items-center">
                                            <i
                                                class="fa-solid fa-user text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                        </div>
                                        <div class="flex justify-center items-center group-hover:text-white">
                                            <span>Personal Information</span>
                                        </div>
                                    </div>

                                    <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C]  transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('super.update-pass') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                        onclick="navigateToPasswordPageSuperAdmin()">
                                        <div class="flex justify-center items-center">
                                            <i
                                                class="fa-solid fa-lock text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                        </div>
                                        <div class="flex justify-center items-center group-hover:text-white">
                                            <span>Password</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white border border-gray-300 dark:bg-[#161616] dark:text-white  dark:border-[#404040] rounded-lg shadow-lg w-full">
                                <div class="px-5 py-6">
                                    <main class="animate-fadeIn">
                                        @yield('settingscontent')
                                    </main>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="flex flex-col justify-center w-full pt-8">
                <div class="flex my-3 rounded-md justify-start items-start">
                    <a href="{{ route('student.dashboard') }}"
                        class="flex gap-2 text-white p-2 dark:hover:bg-[#161616] hover:bg-gray-200 rounded-md cursor-pointer">
                        <div class="text-red-900 dark:text-[#CCAA2C] flex gap-1 justify-center items-center">
                            <i class="fa-solid fa-circle-arrow-left text-2xl"></i>
                        </div>
                        <span class="md:text-lg text-md text-black dark:text-white">Back to class record list</span>
                    </a>
                </div>

                <div class="dark:text-white w-full pb-5 md:px-2 lg:px-12 xl:px-32 2xl:px-56">
                    <div class="flex flex-col gap-3">
                        <div class="w-full flex justify-center items-center">
                            <x-titleText>
                                Account Settings
                            </x-titleText>
                        </div>
                        <div class="flex md:flex-row flex-col gap-10">
                            <div class="flex md:flex-row flex-col gap-2  md:w-96 lg:w-72 w-full">
                                <div class="md:hidden flex justify-end">
                                    <button onclick="toggleSelectionMenu()"
                                        class="bg-red-900 dark:bg-[#CCAA2C] text-white  rounded-md p-2 flex gap-2">
                                        @if (request()->routeIs('student.acc-info'))
                                            <div class="flex gap-2">
                                                <div class="flex justify-center items-center">
                                                    <i
                                                        class="fa-solid fa-user text-lg  rounded-md group-hover:text-white"></i>
                                                </div>
                                                <div
                                                    class="flex justify-center items-center group-hover:text-white text-md">
                                                    <span>Personal Information</span>
                                                </div>
                                            </div>
                                        @elseif (request()->routeIs('student.update-pass'))
                                            <div class="flex gap-2">
                                                <div class="flex justify-center items-center">
                                                    <i
                                                        class="fa-solid fa-lock text-lg  rounded-md group-hover:text-white"></i>
                                                </div>
                                                <div
                                                    class="flex justify-center items-center group-hover:text-white text-md">
                                                    <span>Password</span>
                                                </div>
                                            </div>
                                        @elseif (request()->routeIs('student.archived-records'))
                                            <div class="flex gap-2">
                                                <div class="flex justify-center items-center">
                                                    <i
                                                        class="fa-solid fa-box-archive text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                                </div>
                                                <div class="flex justify-center items-center group-hover:text-white">
                                                    <span>Archived Class Record</span>
                                                </div>
                                            </div>
                                        @elseif(request()->routeIs('student.act-logs'))
                                            <div class="flex gap-2">
                                                <div class="flex justify-center items-center">
                                                    <i
                                                        class="fa-solid fa-list text-lg  rounded-md group-hover:text-white"></i>
                                                </div>
                                                <div
                                                    class="flex justify-center items-center group-hover:text-white text-md">
                                                    <span>Activity Log</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div id="caretIcon">
                                            <i class="fa-solid fa-caret-down text-lg"></i>
                                        </div>
                                    </button>
                                </div>
                                <div id="selectionMenu"
                                    class="hidden md:block transition-all duration-300 animate-fadeIn">
                                    <div
                                        class=" flex flex-col gap-5 md:w-96 lg:w-72 w-full bg-white md:dark:bg-transparent dark:bg-[#161616] rounded-md p-2 shadow-lg md:shadow-none">
                                        <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C]  transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('student.acc-info') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                            onclick="navigateToAccountPageStud()">
                                            <div class="flex justify-center items-center">
                                                <i
                                                    class="fa-solid fa-user text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                            </div>
                                            <div class="flex justify-center items-center group-hover:text-white">
                                                <span>Personal Information</span>
                                            </div>
                                        </div>

                                        <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('student.update-pass') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                            onclick="navigateToPasswordPageStud()">
                                            <div class="flex justify-center items-center">
                                                <i
                                                    class="fa-solid fa-lock text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                            </div>
                                            <div class="flex justify-center items-center group-hover:text-white">
                                                <span>Password</span>
                                            </div>
                                        </div>

                                        <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('student.archived-records') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                            onclick="navigateToStudentArchived()">
                                            <div class="flex justify-center items-center">
                                                <i
                                                    class="fa-solid fa-box-archive text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                            </div>
                                            <div class="flex justify-center items-center group-hover:text-white">
                                                <span>Archived Class Record</span>
                                            </div>
                                        </div>


                                        <div class="group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] transition-all duration-300  hover:bg-red-900 {{ request()->routeIs('student.act-logs') ? 'bg-red-900 text-white dark:bg-[#CCAA2C]' : ' text-red-900 dark:text-[#CCAA2C]' }}"
                                            onclick="navigateToStudentActLogs()">
                                            <div class="flex justify-center items-center">
                                                <i
                                                    class="fa-solid fa-list text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                            </div>
                                            <div class="flex justify-center items-center group-hover:text-white">
                                                <span>Activity Log</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] rounded-lg shadow-lg w-full">
                                <div class="px-5 py-6">
                                    <main class="animate-fadeIn">
                                        @yield('settingscontent')
                                    </main>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endsection
</body>

<script>
    function toggleSelectionMenu() {
        $("#selectionMenu").toggleClass("hidden");

        const caretIcon = $("#caretIcon i");
        if (caretIcon.hasClass("fa-caret-down")) {
            caretIcon.removeClass("fa-caret-down").addClass("fa-caret-up");
        } else {
            caretIcon.removeClass("fa-caret-up").addClass("fa-caret-down");
        }
    }

    function navigateToAccountPage() {
        window.location.href = "{{ route('faculty.acc-info') }}";
    }

    function navigateToPasswordPage() {
        window.location.href = "{{ route('faculty.update-pass') }}";
    }

    function navigateToFacultyArchived() {
        window.location.href = "{{ route('faculty.archived-records') }}";
    }

    function navigateToActLogs() {
        window.location.href = "{{ route('faculty.act-logs') }}";
    }

    function navigateToAccountPageStud() {
        window.location.href = "{{ route('student.acc-info') }}";
    }

    function navigateToPasswordPageStud() {
        window.location.href = "{{ route('student.update-pass') }}";
    }

    function navigateToStudentArchived() {
        window.location.href = "{{ route('student.archived-records') }}";
    }

    function navigateToStudentActLogs() {
        window.location.href = "{{ route('student.act-logs') }}";
    }


    function navigateToAccountPageAdmin() {
        window.location.href = "{{ route('admin.acc-info') }}";
    }

    function navigateToPasswordPageAdmin() {
        window.location.href = "{{ route('admin.update-pass') }}";
    }

    function navigateToClassRecordSetup() {
        window.location.href = "{{ route('admin.class-record-yearSem') }}";
    }

    function navigateToAccountPageSuperAdmin() {
        window.location.href = "{{ route('super.acc-info') }}";
    }

    function navigateToPasswordPageSuperAdmin() {
        window.location.href = "{{ route('super.update-pass') }}";
    }
</script>

</html>
