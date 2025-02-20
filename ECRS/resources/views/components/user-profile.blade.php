<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    @vite('resources/js/top-navbar.js')
</head>

<body>
    @if ($role == 1)
        <div>
            <div id="prof-profile" data-profile="prof-modal"  class="flex justify-center items-center rounded-md">
                <div
                    class="flex justify-center items-center cursor-pointer dark:hover:bg-[#1E1E1E]  hover:bg-red-800  gap-2 text-lg p-2 rounded-md relative">
                    {{-- <i class="fa-solid fa-circle-user text-3xl text-white dark:text-[#CCAA2C]"></i> --}}
                    <x-avatar :userinfo="$userinfo"  />
                    <div class="hidden sm:inline">
                        <div class="text-white flex gap-2 text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md font-bold">
                            <p>{{ ucwords($userinfo->salutation) }}</p>
                            <p>{{ ucwords($userinfo->Fname) }}</p>
                            <p>{{ ucwords($userinfo->Lname) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <div id="prof-modal" 
                    class="hidden z-40 absolute text-center rounded-lg bg-white dark:bg-[#161616] border border-gray-300 dark:border-[#404040] shadow-lg mt-2 md:w-72 w-56">
                    <div class="flex flex-col gap-2 p-2">
                        <div>
                            <div class="flex gap-2 font-bold text-black dark:text-[#CCAA2C]   justify-center text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">
                                <p>{{ ucwords($userinfo->salutation) }}</p>
                                <p>{{ ucwords($userinfo->Fname) }}</p>
                                <p>{{ ucwords($userinfo->Lname) }}</p>
                            </div>
                            <hr class="bg-gray-100 mt-2">
                        </div>
                        <div>
                            <ul class="flex flex-col gap-2 ">
                                <li class="md:w-full  group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] dark:text-[#CCAA2C] dark:hover:text-black text-red-900 transition-all duration-300  hover:bg-red-900"
                                    onclick="navigateToFacultySettings()">
                                    <div class="flex justify-center items-center">
                                        <i class="fa-solid fa-gear text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                    </div>
                                    <div class="flex justify-center items-center group-hover:text-white">
                                        <span class="text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">Account Settings</span>
                                    </div>
                                </li>
                                <a href="/logout" id="myLogout"
                                    class=" group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold  dark:hover:bg-[#CCAA2C] dark:text-[#CCAA2C] dark:hover:text-black text-red-900  transition-all duration-300  hover:bg-red-900">
                                    <div class="flex justify-center items-center">
                                        <i
                                            class="fa-solid fa-right-from-bracket text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                    </div>
                                    <div class="flex justify-center items-center group-hover:text-white">
                                        <span class="text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">Logout</span>
                                    </div>
                                </a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($role == 2)
        <div>
            <div id="admin-profile" data-profile="admin-modal"  class="flex justify-center items-center rounded-md">
                <div
                    class="flex justify-center items-center cursor-pointer dark:hover:bg-[#1E1E1E] hover:bg-red-800 gap-2 text-lg p-3 rounded-md">
                    {{-- <i class="fa-solid fa-circle-user text-3xl text-white dark:text-[#CCAA2C]"></i> --}}
                    <x-avatar :userinfo="$userinfo"  />
                    <div class="text-white flex gap-2 text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md font-bold">
                        <p>{{ ucwords($userinfo->salutation) }}</p>
                        <p>{{ ucwords($userinfo->Fname) }}</p>
                        <p>{{ ucwords($userinfo->Lname) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <div id="admin-modal"
                    class="hidden z-40 absolute text-center rounded-lg bg-white dark:bg-[#161616] border border-gray-300 dark:border-[#404040] shadow-lg mt-2 md:w-72 w-56">
                    <div class="flex flex-col gap-2 p-2">
                        <div>
                            <div class="flex gap-2 font-bold text-black dark:text-[#CCAA2C] justify-center text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">
                                <p>{{ ucwords($userinfo->salutation) }}</p>
                                <p>{{ ucwords($userinfo->Fname) }}</p>
                                <p>{{ ucwords($userinfo->Lname) }}</p>
                            </div>
                            <hr class="bg-gray-100 mt-2">
                        </div>
                        <div>
                            <ul class="flex flex-col gap-2">
                                <li class=" group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] dark:text-[#CCAA2C] dark:hover:text-black text-red-900 transition-all duration-300  hover:bg-red-900"
                                    onclick="navigateToAdminSettings()">
                                    <div class="flex justify-center items-center">
                                        <i class="fa-solid fa-gear text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                    </div>
                                    <div class="flex justify-center items-center group-hover:text-white text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">
                                        <span>Account Settings</span>
                                    </div>
                                </li>
                                <a href="/logout" id="myLogout"
                                    class=" group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] dark:text-[#CCAA2C] dark:hover:text-black text-red-900 transition-all duration-300  hover:bg-red-900">
                                    <div class="flex justify-center items-center">
                                        <i
                                            class="fa-solid fa-right-from-bracket text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                    </div>
                                    <div class="flex justify-center items-center group-hover:text-white text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">
                                        <span>Logout</span>
                                    </div>
                                </a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($role == 4)
        <div>
            <div id="superadmin-profile" data-profile="superadmin-modal"  class="flex justify-center items-center rounded-md">
                <div
                    class="flex justify-center items-center cursor-pointer dark:hover:bg-[#1E1E1E] hover:bg-red-800 text-lg p-3 rounded-md">
                    {{-- <i class="fa-solid fa-circle-user text-3xl text-white dark:text-[#CCAA2C]"></i> --}}
                    <x-avatar :userinfo="$userinfo"  />
                    <div class="text-white flex gap-2  text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md font-bold">
                        <p>{{ ucwords($userinfo->salutation) }}</p>
                        <p>{{ ucwords($userinfo->Fname) }}</p>
                        <p>{{ ucwords($userinfo->Lname) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <div id="superadmin-modal" 
                    class="hidden z-40 absolute text-center rounded-lg bg-white dark:bg-[#161616] border border-gray-300 dark:border-[#404040] shadow-lg mt-2 md:w-72 w-56">
                    <div class="flex flex-col gap-2 p-2">
                        <div>
                            <div class="flex gap-2 font-bold text-black dark:text-[#CCAA2C] justify-center text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">
                                <p>{{ ucwords($userinfo->salutation) }}</p>
                                <p>{{ ucwords($userinfo->Fname) }}</p>
                                <p>{{ ucwords($userinfo->Lname) }}</p>
                            </div>
                            <hr class="bg-gray-100 mt-2">
                        </div>
                        <div>
                            <ul class="flex flex-col gap-2">
                                <li class=" group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] dark:text-[#CCAA2C] dark:hover:text-black text-red-900 transition-all duration-300  hover:bg-red-900"
                                    onclick="navigateToSuperAdminSettings()">
                                    <div class="flex justify-center items-center">
                                        <i class="fa-solid fa-gear text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                    </div>
                                    <div class="flex justify-center items-center group-hover:text-white text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">
                                        <span>Account Settings</span>
                                    </div>
                                </li>
                                <a href="{{ route('logout') }}"  id="myLogout"
                                    class=" group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] dark:text-[#CCAA2C] dark:hover:text-black text-red-900 transition-all duration-300  hover:bg-red-900">
                                    <div class="flex justify-center items-center">
                                        <i
                                            class="fa-solid fa-right-from-bracket text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                    </div>
                                    <div class="flex justify-center items-center group-hover:text-white text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">
                                        <span>Logout</span>
                                    </div>
                                </a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div>
            <div id="student-profile" data-profile="student-modal"  class="flex justify-center items-center rounded-md">
                <div
                    class="flex justify-center items-center cursor-pointer dark:hover:bg-[#1E1E1E] hover:bg-red-800  text-lg p-3 rounded-md">
                    {{-- <i class="fa-solid fa-circle-user text-3xl text-white dark:text-[#CCAA2C]"></i> --}}
                    <x-avatar :userinfo="$userinfo"  />
                    <div class="hidden sm:inline">
                        <div class="text-white flex gap-2 text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md font-bold">
                            <p>{{ ucwords($userinfo->salutation) }}</p>
                            <p>{{ ucwords($userinfo->Fname) }}</p>
                            {{-- <p>{{ ucwords($userinfo->Lname) }}</p> --}}
                        </div>
                    </div>

                </div>
            </div>
            <div class="flex justify-end w-full">
                <div id="student-modal" 
                    class="hidden z-40 absolute text-center rounded-lg bg-white dark:bg-[#161616] border border-gray-300 dark:border-[#404040] shadow-lg mt-2">
                    <div class="flex flex-col gap-2 p-2">
                        <div class="w-full flex flex-col">
                            <div class="flex gap-2 font-bold text-black dark:text-[#CCAA2C] md:w-72 w-56  justify-center text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">
                                <p>{{ ucwords($userinfo->Fname) }}</p>
                                <p>{{ ucwords($userinfo->Lname) }}</p>
                            </div>
                            <hr class="bg-gray-100 mt-2">
                        </div>
                        <div>
                            <ul class="flex flex-col gap-1">
                                <li class="group  rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] dark:text-[#CCAA2C] dark:hover:text-black text-red-900 transition-all duration-300  hover:bg-red-900"
                                    onclick="navigateToStudentSettings()">
                                    <div class="flex justify-center items-center">
                                        <i class="fa-solid fa-gear text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                    </div>
                                    <div class="flex justify-center items-center group-hover:text-white ">
                                        <span class="text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">Account Settings</span>
                                    </div>
                                </li>
                                <a href="/logout" id="myLogout"
                                    class=" group rounded-2xl cursor-pointer p-1 text-left flex flex-row text-base font-bold dark:hover:bg-[#CCAA2C] dark:text-[#CCAA2C] dark:hover:text-black text-red-900 transition-all duration-300  hover:bg-red-900">
                                    <div class="flex justify-center items-center">
                                        <i
                                            class="fa-solid fa-right-from-bracket text-2xl  p-2 rounded-md group-hover:text-white"></i>
                                    </div>
                                    <div class="flex justify-center items-center group-hover:text-white">
                                        <span class="text-sm md:text-md lg:text-md xl:text-[16px] 2xl:text-md">Logout</span>
                                    </div>
                                </a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <x-loader modalLoaderId="loader-modal" titleLoader="Logging out" />

</body>
<script>
    function navigateToFacultySettings() {
        window.location.href = "{{ route('faculty.acc-info') }}";
    }

    function navigateToStudentSettings() {
        window.location.href = "{{ route('student.acc-info') }}";
    }

    function navigateToAdminSettings() {
        window.location.href = "{{ route('admin.acc-info') }}";
    }

    function navigateToSuperAdminSettings() {
        window.location.href = "{{ route('super.acc-info') }}";
    }
</script>

</html>
