@php
    $roleNum = session('role');
    $branch = session('branch');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/js/app.js')
</head>

<body>

    @if ($roleNum == 2)
        <div
            class="bg-red-800 dark:bg-[#161616] w-20 hover:w-60 transition-all duration-300 font-bold fixed z-10 h-full flex justify-center hover:justify-start group ">
            <div class="relative">
                <ul class="flex flex-col gap-2 mt-16">
                    {{-- <li class="rounded-2xl cursor-pointer hover:bg-red-900 p-3 text-left flex flex-row text-base  font-bold hover:w-56 ml-2 group dark:hover:bg-[#1E1E1E] dark:hover:text-white text-white"
                    onclick="navigateToDashboard()">
                    <div class="flex justify-center items-center">
                        <i
                            class="fa-solid fa-square-poll-vertical text-4xl transition-all duration-300  p-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'text-red-900 bg-white dark:text-[#CCAA2C]' : 'dark:text-[#CCAA2C] text-white' }}"></i>
                    </div>
                    <div class="flex justify-center items-center pl-2">
                        <span class="hidden group-hover:block">Dashboard</span>
                    </div>
                </li> --}}
                    <li class="rounded-2xl cursor-pointer hover:bg-red-900 p-3 text-left flex flex-row text-base font-bold hover:w-56 ml-2 group dark:hover:bg-[#1E1E1E] dark:hover:text-white text-white"
                        onclick="navigateToAccounts()">
                        <div class="flex justify-center items-center">
                            <i
                                class="fa-solid fa-users text-2xl transition-all duration-300 p-2 rounded-md {{ request()->routeIs('admin.accounts') ? 'text-red-900 bg-white dark:text-[#CCAA2C]' : 'dark:text-[#CCAA2C] text-white' }}"></i>
                        </div>
                        <div class="flex justify-center items-center pl-2">
                            <span class="hidden group-hover:block">Accounts</span>
                        </div>
                    </li>
                    <li class="rounded-2xl cursor-pointer hover:bg-red-900 p-3 text-left flex flex-row text-base font-bold hover:w-56 ml-2 group dark:hover:bg-[#1E1E1E] dark:hover:text-white text-white"
                        onclick="navigateToAdminCourseList()">
                        <div class="flex justify-center items-center">
                            <i
                                class="fa-solid fa-bars text-3xl transition-all duration-300 p-2 rounded-md {{ request()->routeIs('admin.course-list') || request()->routeIs('admin.program-list') ? 'text-red-900 bg-white dark:text-[#CCAA2C]' : 'dark:text-[#CCAA2C] text-white' }}"></i>
                        </div>
                        <div class="flex justify-center items-center pl-2">
                            <span class="hidden group-hover:block">Course Lists</span>
                        </div>
                    </li>
                    <li class="rounded-2xl cursor-pointer hover:bg-red-900 p-3 text-left flex flex-row text-base font-bold hover:w-56 ml-2 group dark:hover:bg-[#1E1E1E] dark:hover:text-white text-white"
                        onclick="navigateToReports()">
                        <div class="flex justify-center items-center">
                            <i
                                class="fa-solid fa-paste text-2xl transition-all duration-300 p-2 rounded-md {{ request()->routeIs('admin.to-verify-report') || request()->routeIs('admin.verified-report') || request()->routeIs('admin.view-to-verify-report') || request()->routeIs('admin.class-record-report') ? 'text-red-900 bg-white dark:text-[#CCAA2C]' : 'dark:text-[#CCAA2C] text-white' }}"></i>
                        </div>
                        <div class="flex justify-center items-center pl-2">
                            <span class="hidden group-hover:block">Class Record Reports</span>
                        </div>
                    </li>

                    @if ($branch == 1)
                        <li class="rounded-2xl cursor-pointer hover:bg-red-900 p-3 text-left flex flex-row text-base font-bold hover:w-56 ml-2 group dark:hover:bg-[#1E1E1E] dark:hover:text-white text-white"
                            onclick="navigateToFacultyLoads()">
                            <div class="flex justify-center items-center">
                                <i
                                    class="fa-solid fa-clipboard text-3xl transition-all duration-300 p-2 rounded-md {{ request()->routeIs('admin.faculty-loads-page') ? 'text-red-900 bg-white dark:text-[#CCAA2C]' : 'dark:text-[#CCAA2C] text-white' }}"></i>
                            </div>
                            <div class="flex justify-center items-center pl-2">
                                <span class="hidden group-hover:block">Faculty Loads</span>
                            </div>
                        </li>
                    @endif
                    <li class="rounded-2xl cursor-pointer hover:bg-red-900 p-3 text-left flex flex-row text-base font-bold hover:w-56 ml-2 group dark:hover:bg-[#1E1E1E] dark:hover:text-white text-white"
                        onclick="navigateToAdminActLogs()">
                        <div class="flex justify-center items-center">
                            <i
                                class="fa-solid fa-list text-2xl transition-all duration-300 p-2 rounded-md {{ request()->routeIs('admin.act-logs') ? 'text-red-900 bg-white dark:text-[#CCAA2C]' : 'dark:text-[#CCAA2C] text-white' }}"></i>
                        </div>
                        <div class="flex justify-center items-center pl-2">
                            <span class="hidden group-hover:block">Activity Log</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    @else
        <div
            class="bg-red-800 dark:bg-[#161616] w-20 hover:w-60 transition-all duration-300 font-bold fixed z-10 h-full flex justify-center hover:justify-start group ">
            <div class="relative">
                <ul class="flex flex-col gap-2 mt-16">
                    {{-- <li class="rounded-2xl cursor-pointer hover:bg-red-900 p-3 text-left flex flex-row text-base  font-bold hover:w-56 ml-2 group dark:hover:bg-[#1E1E1E] dark:hover:text-white text-white"
                    onclick="navigateToSuperDashboard()">
                    <div class="flex justify-center items-center">
                        <i
                            class="fa-solid fa-square-poll-vertical text-4xl transition-all duration-300  p-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'text-red-900 bg-white dark:text-[#CCAA2C]' : 'dark:text-[#CCAA2C] text-white' }}"></i>
                    </div>
                    <div class="flex justify-center items-center pl-2">
                        <span class="hidden group-hover:block">Dashboard</span>
                    </div>
                </li> --}}
                    <li class="rounded-2xl cursor-pointer hover:bg-red-900 p-3 text-left flex flex-row text-base font-bold hover:w-56 ml-2 group dark:hover:bg-[#1E1E1E] dark:hover:text-white text-white"
                        onclick="navigateToSuperAccounts()">
                        <div class="flex justify-center items-center">
                            <i
                                class="fa-solid fa-users text-2xl transition-all duration-300 p-2 rounded-md {{ request()->routeIs('super.accounts') ? 'text-red-900 bg-white dark:text-[#CCAA2C]' : 'dark:text-[#CCAA2C] text-white' }}"></i>
                        </div>
                        <div class="flex justify-center items-center pl-2">
                            <span class="hidden group-hover:block">Accounts</span>
                        </div>
                    </li>
                    <li class="rounded-2xl cursor-pointer hover:bg-red-900 p-3 text-left flex flex-row text-base font-bold hover:w-56 ml-2 group dark:hover:bg-[#1E1E1E] dark:hover:text-white text-white"
                        onclick="navigateToSuperAdminBranches()">
                        <div class="flex justify-center items-center">
                            <i
                                class="fa-solid fa-bars text-3xl transition-all duration-300 p-2 rounded-md {{ request()->routeIs('super.branch-list') || request()->routeIs('super.branch-list') ? 'text-red-900 bg-white dark:text-[#CCAA2C]' : 'dark:text-[#CCAA2C] text-white' }}"></i>
                        </div>
                        <div class="flex justify-center items-center pl-2">
                            <span class="hidden group-hover:block">Branches</span>
                        </div>
                    </li>

                    <li class="rounded-2xl cursor-pointer hover:bg-red-900 p-3 text-left flex flex-row text-base font-bold hover:w-56 ml-2 group dark:hover:bg-[#1E1E1E] dark:hover:text-white text-white"
                        onclick="navigateToSuperActLogs()">
                        <div class="flex justify-center items-center">
                            <i
                                class="fa-solid fa-list text-2xl transition-all duration-300 p-2 rounded-md {{ request()->routeIs('super.act-logs') ? 'text-red-900 bg-white dark:text-[#CCAA2C]' : 'dark:text-[#CCAA2C] text-white' }}"></i>
                        </div>
                        <div class="flex justify-center items-center pl-2">
                            <span class="hidden group-hover:block">Activity Log</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    @endif
    <script>
        function navigateToDashboard() {
            window.location.href = "{{ route('admin.dashboard') }}";
        }

        function navigateToAccounts() {
            window.location.href = "{{ route('admin.accounts') }}";
        }

        function navigateToAdminCourseList() {
            window.location.href = "{{ route('admin.course-list') }}";
        }


        function navigateToReports() {
            window.location.href = "{{ route('admin.class-record-report') }}";
        }

        function navigateToFacultyLoads() {
            window.location.href = "{{ route('admin.faculty-loads-page') }}";
        }

        function navigateToOrgChart() {
            window.location.href = "{{ route('admin.org-chart') }}";
        }

        function navigateToClassRecord() {
            window.location.href = "{{ route('faculty.class-record') }}";
        }

        function navigateToCourseList() {
            window.location.href = "{{ route('faculty.course-list') }}";
        }

        function navigateToProgramList() {
            window.location.href = "{{ route('faculty.program-list') }}";
        }

        function navigateToSubmitted() {
            window.location.href = "{{ route('faculty.submitted-report') }}";
        }


        function navigateToSuperDashboard() {
            window.location.href = "{{ route('super.dashboard') }}";
        }

        function navigateToSuperAccounts() {
            window.location.href = "{{ route('super.accounts') }}";
        }

        function navigateToSuperAdminBranches() {
            window.location.href = "{{ route('super.branch-list') }}";
        }

        function navigateToAdminActLogs() {
            window.location.href = "{{ route('admin.act-logs') }}";
        }


        function navigateToSuperActLogs() {
            window.location.href = "{{ route('super.act-logs') }}";
        }
    </script>
</body>

</html>
